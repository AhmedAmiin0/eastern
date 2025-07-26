<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Currency;
use App\Dto\CurrencyDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CountryRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Country::class);
  }
  public function getCountriesWithCurrency(int $limit = 50, int $offset = 0): array
  {
    return $this->createQueryBuilder('c')
      ->leftJoin('c.currency', 'currency')
      ->addSelect('currency')
      ->setMaxResults($limit)
      ->setFirstResult($offset)
      ->getQuery()
      ->getArrayResult();
  }

  public function findOrCreateCurrency(CurrencyDto $currencyDto): Currency
  {
    $currency = $this->getEntityManager()->getRepository(Currency::class)->findOneBy(['symbol' => $currencyDto->getSymbol()]);
    
    if (!$currency) {
      $currency = new Currency();
      $currency->setName($currencyDto->getName());
      $currency->setSymbol($currencyDto->getSymbol());
      $this->getEntityManager()->persist($currency);
    }

    return $currency;
  }
}
