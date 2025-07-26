<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CountryDto;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\Uuid;

class CountryService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CountryRepository $countryRepository,
        private NormalizerInterface $normalizer,
        private ValidationService $validationService
    ) {}

    public function getCountries(int $limit = 300, int $offset = 0): array
    {
        $countries = $this->countryRepository->getCountriesWithCurrency($limit, $offset);
        return $this->normalizer->normalize($countries, 'json', ['groups' => ['country']]);
    }

    public function getCountry(Country $country): array
    {
        return $this->normalizer->normalize($country, 'json', ['groups' => ['country']]);
    }

    public function createCountry(string $requestContent): array
    {
        $countryDto = $this->validationService->validateRequestData($requestContent, CountryDto::class);

        $country = new Country();
        $country->setUuid(Uuid::v4());
        $country->setName($countryDto->getName());
        $country->setRegion($countryDto->getRegion());
        $country->setSubRegion($countryDto->getSubRegion());
        $country->setDemonym($countryDto->getDemonym());
        $country->setPopulation($countryDto->getPopulation());
        $country->setIndependant($countryDto->getIndependant());
        $country->setFlag($countryDto->getFlag());

        if ($countryDto->getCurrency()) {
            $currency = $this->countryRepository->findOrCreateCurrency($countryDto->getCurrency());
            $country->setCurrency($currency);
        }

        $this->entityManager->persist($country);
        $this->entityManager->flush();

        return $this->normalizer->normalize($country, 'json', ['groups' => ['country']]);
    }

    public function updateCountry(Country $country, string $requestContent): array
    {
        $countryDto = $this->validationService->validateRequestData($requestContent, CountryDto::class);

        $country->setName($countryDto->getName());
        $country->setRegion($countryDto->getRegion());
        $country->setSubRegion($countryDto->getSubRegion());
        $country->setDemonym($countryDto->getDemonym());
        $country->setPopulation($countryDto->getPopulation());
        $country->setIndependant($countryDto->getIndependant());
        $country->setFlag($countryDto->getFlag());

        if ($countryDto->getCurrency()) {
            $currency = $this->countryRepository->findOrCreateCurrency($countryDto->getCurrency());
            $country->setCurrency($currency);
        }

        $this->entityManager->persist($country);
        $this->entityManager->flush();

        return $this->normalizer->normalize($country, 'json', ['groups' => ['country']]);
    }

    public function deleteCountry(Country $country): void
    {
        $this->entityManager->remove($country);
        $this->entityManager->flush();
    }
} 