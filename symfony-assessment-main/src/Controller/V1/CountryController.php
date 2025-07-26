<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\Country;
use App\Service\CountryService;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('countries')]
class CountryController extends AbstractController
{
    public function __construct(
        private CountryService $countryService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/list', methods: ['GET'])]
    #[OA\Parameter(name: 'limit', description: 'Number of countries to return (default: 250, max: 500)', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 500, default: 250))]
    #[OA\Parameter(name: 'offset', description: 'Number of countries to skip for pagination (default: 0)', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 500, default: 0))]
    #[OA\Response(response: 200, description: 'Countries retrieved successfully')]
    #[OA\Tag(name: 'Countries')]
    public function getCountries(Request $request): JsonResponse
    {
        $limit = (int) $request->query->get('limit', 250);
        $offset = (int) $request->query->get('offset', 0);
        
        $data = $this->countryService->getCountries($limit, $offset);
        return new JsonResponse([
            'message' => 'Countries retrieved successfully',
            'data' => $data,
        ]);
    }

    #[Route('', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Country data to create',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Canada', minLength: 1, maxLength: 255),
                new OA\Property(property: 'region', type: 'string', example: 'Americas', minLength: 1, maxLength: 255),
                new OA\Property(property: 'subRegion', type: 'string', example: 'North America', minLength: 1, maxLength: 255),
                new OA\Property(property: 'demonym', type: 'string', example: 'Canadian', minLength: 1, maxLength: 255),
                new OA\Property(property: 'population', type: 'integer', example: 38005238),
                new OA\Property(property: 'independant', type: 'boolean', example: true),
                new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png', format: 'uri', maxLength: 255),
                new OA\Property(
                    property: 'currency',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar'),
                        new OA\Property(property: 'symbol', type: 'string', example: 'CAD')
                    ]
                )
            ],
            required: ['name', 'region', 'subRegion', 'demonym', 'population', 'independant', 'flag']
        )
    )]
    #[OA\Response(response: 201, description: 'Country created successfully')]
    #[OA\Response(response: 400, description: 'Validation error')]
    #[OA\Tag(name: 'Countries')]
    public function addCountry(Request $request): JsonResponse
    {
        $data = $this->countryService->createCountry($request->getContent());
        return new JsonResponse([
            'message' => 'Country created successfully',
            'data' => $data,
        ], 201);
    }

    #[Route('/{country}', methods: ['GET'], requirements: ['country' => '\d+'])]
    #[OA\Parameter(name: 'country', description: 'Country ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Country retrieved successfully')]
    #[OA\Response(response: 404, description: 'Country not found')]
    #[OA\Tag(name: 'Countries')]
    public function getCountry(Country $country): JsonResponse
    {   
        $data = $this->countryService->getCountry($country);
        return new JsonResponse([
            'message' => 'Country retrieved successfully',
            'data' => $data,
        ]);
    }
    
    #[Route('/{country}', methods: ['PATCH'], requirements: ['country' => '\d+'])]
    #[OA\Parameter(name: 'country', description: 'Country ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        description: 'Country data to update (all fields optional)',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Canada', minLength: 1, maxLength: 255),
                new OA\Property(property: 'region', type: 'string', example: 'Americas', minLength: 1, maxLength: 255),
                new OA\Property(property: 'subRegion', type: 'string', example: 'North America', minLength: 1, maxLength: 255),
                new OA\Property(property: 'demonym', type: 'string', example: 'Canadian', minLength: 1, maxLength: 255),
                new OA\Property(property: 'population', type: 'integer', example: 38005238),
                new OA\Property(property: 'independant', type: 'boolean', example: true),
                new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png', format: 'uri', maxLength: 255),
                new OA\Property(
                    property: 'currency',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar'),
                        new OA\Property(property: 'symbol', type: 'string', example: 'CAD')
                    ]
                )
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Country updated successfully')]
    #[OA\Response(response: 400, description: 'Validation error')]
    #[OA\Response(response: 404, description: 'Country not found')]
    #[OA\Tag(name: 'Countries')]
    public function updateCountry(int $country, Request $request): JsonResponse
    {
        $countryEntity = $this->entityManager->getRepository(Country::class)->find($country);
        
        if (!$countryEntity) {
            throw $this->createNotFoundException('Country not found');
        }
        
        $data = $this->countryService->updateCountry($countryEntity, $request->getContent());
        return new JsonResponse([
            'message' => 'Country updated successfully',
            'data' => $data,
        ]);
    }

    #[Route('/{country}', methods: ['DELETE'], requirements: ['country' => '\d+'])]
    #[OA\Parameter(name: 'country', description: 'Country ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Country deleted successfully')]
    #[OA\Response(response: 404, description: 'Country not found')]
    #[OA\Tag(name: 'Countries')]
    public function deleteCountry(int $country): JsonResponse
    {
        $countryEntity = $this->entityManager->getRepository(Country::class)->find($country);
        
        if (!$countryEntity) {
            throw $this->createNotFoundException('Country not found');
        }
        
        $this->countryService->deleteCountry($countryEntity);
        return new JsonResponse([
            'message' => 'Country deleted successfully',
            'data' => null,
        ]);
    }
}
