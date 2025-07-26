<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Country;
use App\Entity\Currency;
use App\Repository\CountryRepository;
use App\Dto\CurrencyDto;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Uid\Uuid;

class CountrySyncService
{
    public function __construct(
        private HttpClientInterface $httpClient, 
        private EntityManagerInterface $entityManager,
        private CountryRepository $countryRepository,
        #[Autowire(param: 'app.rest_countries_api_url')]
        private string $baseUrl,
        private LoggerInterface $logger
    ) {}

    public function syncCountries(): int
    {
        $apiCountries = $this->httpClient->request('GET', $this->baseUrl)->toArray();
        $existingCountries = $this->countryRepository->findAll();
        
        $this->logger->info('Starting sync with ' . count($apiCountries) . ' countries from API');

        $stats = $this->processCountries($apiCountries, $existingCountries);
        $this->entityManager->flush();
        
        $this->logger->info("Sync completed: {$stats['created']} created, {$stats['updated']} updated, {$stats['deleted']} deleted");
        
        return $stats['total'];
    }

    private function processCountries(array $apiCountries, array $existingCountries): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'deleted' => 0, 'total' => 0];
        $existingMap = array_column($existingCountries, null, 'name');
        $apiNames = array_column($apiCountries, 'name.common');

        foreach ($apiCountries as $countryData) {
            $name = $countryData['name']['common'];
            
            if (isset($existingMap[$name])) {
                $this->updateCountry($existingMap[$name], $countryData);
                $stats['updated']++;
            } else {
                $this->entityManager->persist($this->createCountry($countryData));
                $stats['created']++;
            }
            $stats['total']++;
            $this->logger->info(($stats['updated'] > 0 ? "Updated" : "Created") . ": {$name}");
        }

        foreach ($existingCountries as $existingCountry) {
            if (!in_array($existingCountry->getName(), $apiNames)) {
                $this->entityManager->remove($existingCountry);
                $stats['deleted']++;
                $this->logger->info("Deleted: {$existingCountry->getName()}");
            }
        }

        return $stats;
    }

    private function createCountry(array $countryData): Country
    {
        $country = new Country();
        $country->setUuid(Uuid::v4());
        $country->setName($countryData['name']['common']);
        $this->populateCountryData($country, $countryData);
        return $country;
    }

    private function updateCountry(Country $country, array $countryData): void
    {
        $this->populateCountryData($country, $countryData);
        $this->entityManager->persist($country);
    }

    private function populateCountryData(Country $country, array $countryData): void
    {
        $country->setRegion($countryData['region'] ?? null);
        $country->setSubRegion($countryData['subregion'] ?? null);
        $country->setDemonym($countryData['demonyms']['eng']['m'] ?? null);
        $country->setPopulation($countryData['population'] ?? null);
        $country->setIndependant($countryData['independent'] ?? null);
        $country->setFlag($countryData['flags']['png'] ?? null);

        if (!empty($countryData['currencies'])) {
            $currencyCode = array_key_first($countryData['currencies']);
            $currencyData = $countryData['currencies'][$currencyCode];
            
            $currencyDto = new CurrencyDto();
            $currencyDto->setName($currencyData['name'] ?? $currencyCode);
            $currencyDto->setSymbol($currencyCode);
            
            $currency = $this->countryRepository->findOrCreateCurrency($currencyDto);
            $country->setCurrency($currency);
        } 
    }
} 