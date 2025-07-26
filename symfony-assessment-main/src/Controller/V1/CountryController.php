<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CountryService;
use OpenApi\Attributes as OA;

#[Route('countries')]
class CountryController extends AbstractController
{
    public function __construct(
        private CountryService $countryService
    ) {}

    #[Route('/list', methods: ['GET'])]
    #[OA\Parameter(
        name: 'limit',
        description: 'Number of countries to return (default: 250, max: 500)',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 500, default: 250)
    )]
    #[OA\Parameter(
        name: 'offset',
        description: 'Number of countries to skip for pagination (default: 0)',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 0, default: 0)
    )]
    #[OA\Response(
        response: 200,
        description: 'Countries retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Countries retrieved successfully'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'uuid', type: 'string', example: '14897071-6cb0-4ded-a79b-4e4a50b7d823', format: 'uuid'),
                            new OA\Property(property: 'name', type: 'string', example: 'Canada'),
                            new OA\Property(property: 'region', type: 'string', example: 'Americas'),
                            new OA\Property(property: 'subRegion', type: 'string', example: 'North America'),
                            new OA\Property(property: 'demonym', type: 'string', example: 'Canadian'),
                            new OA\Property(property: 'population', type: 'integer', example: 38005238),
                            new OA\Property(property: 'independant', type: 'boolean', example: true),
                            new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png'),
                            new OA\Property(
                                property: 'currency',
                                type: 'object',
                                nullable: true,
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar'),
                                    new OA\Property(property: 'symbol', type: 'string', example: 'CAD')
                                ]
                            )
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Tag(name: 'Countries')]
    public function getCountries(Request $request): JsonResponse
    {
        $limit = $request->query->getInt('limit', 250);
        $offset = $request->query->getInt('offset', 0);
        
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
                new OA\Property(property: 'name', type: 'string', example: 'Canada', minLength: 1),
                new OA\Property(property: 'region', type: 'string', example: 'Americas', minLength: 1),
                new OA\Property(property: 'subRegion', type: 'string', example: 'North America', minLength: 1),
                new OA\Property(property: 'demonym', type: 'string', example: 'Canadian', minLength: 1),
                new OA\Property(property: 'population', type: 'integer', example: 38005238, minimum: 0),
                new OA\Property(property: 'independant', type: 'boolean', example: true),
                new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png', format: 'uri'),
                new OA\Property(
                    property: 'currency',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar', minLength: 1),
                        new OA\Property(property: 'symbol', type: 'string', example: 'CAD', minLength: 3, maxLength: 3)
                    ]
                )
            ],
            required: ['name', 'region', 'subRegion', 'demonym', 'population', 'independant', 'flag']
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Country created successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Country created successfully'),
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'uuid', type: 'string', example: '14897071-6cb0-4ded-a79b-4e4a50b7d823', format: 'uuid'),
                        new OA\Property(property: 'name', type: 'string', example: 'Canada'),
                        new OA\Property(property: 'region', type: 'string', example: 'Americas'),
                        new OA\Property(property: 'subRegion', type: 'string', example: 'North America'),
                        new OA\Property(property: 'demonym', type: 'string', example: 'Canadian'),
                        new OA\Property(property: 'population', type: 'integer', example: 38005238),
                        new OA\Property(property: 'independant', type: 'boolean', example: true),
                        new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png'),
                        new OA\Property(
                            property: 'currency',
                            type: 'object',
                            nullable: true,
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar'),
                                new OA\Property(property: 'symbol', type: 'string', example: 'CAD')
                            ]
                        )
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Validation failed'),
                new OA\Property(property: 'status', type: 'integer', example: 400),
                new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string'), example: ['Name is required'])
            ]
        )
    )]
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
    public function getCountry(Country $country): JsonResponse
    {   
        $data = $this->countryService->getCountry($country);
        return new JsonResponse([
            'message' => 'Country retrieved successfully',
            'data' => $data,
        ]);
    }
    
    #[Route('/{country}', methods: ['PATCH'], requirements: ['country' => '\d+'])]
    #[OA\Parameter(
        name: 'country',
        description: 'Country ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\RequestBody(
        description: 'Country data to update (all fields optional)',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Canada', minLength: 1),
                new OA\Property(property: 'region', type: 'string', example: 'Americas', minLength: 1),
                new OA\Property(property: 'subRegion', type: 'string', example: 'North America', minLength: 1),
                new OA\Property(property: 'demonym', type: 'string', example: 'Canadian', minLength: 1),
                new OA\Property(property: 'population', type: 'integer', example: 38005238, minimum: 0),
                new OA\Property(property: 'independant', type: 'boolean', example: true),
                new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png', format: 'uri'),
                new OA\Property(
                    property: 'currency',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar', minLength: 1),
                        new OA\Property(property: 'symbol', type: 'string', example: 'CAD', minLength: 3, maxLength: 3)
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Country updated successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Country updated successfully'),
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'uuid', type: 'string', example: '14897071-6cb0-4ded-a79b-4e4a50b7d823', format: 'uuid'),
                        new OA\Property(property: 'name', type: 'string', example: 'Canada'),
                        new OA\Property(property: 'region', type: 'string', example: 'Americas'),
                        new OA\Property(property: 'subRegion', type: 'string', example: 'North America'),
                        new OA\Property(property: 'demonym', type: 'string', example: 'Canadian'),
                        new OA\Property(property: 'population', type: 'integer', example: 38005238),
                        new OA\Property(property: 'independant', type: 'boolean', example: true),
                        new OA\Property(property: 'flag', type: 'string', example: 'https://flagcdn.com/w320/ca.png'),
                        new OA\Property(
                            property: 'currency',
                            type: 'object',
                            nullable: true,
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Canadian dollar'),
                                new OA\Property(property: 'symbol', type: 'string', example: 'CAD')
                            ]
                        )
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Validation failed'),
                new OA\Property(property: 'status', type: 'integer', example: 400),
                new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string'), example: ['Name is required'])
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Country not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Resource not found'),
                new OA\Property(property: 'status', type: 'integer', example: 404)
            ]
        )
    )]
    #[OA\Tag(name: 'Countries')]
    public function updateCountry(Country $countryEntity, Request $request): JsonResponse
    {
        $data = $this->countryService->updateCountry($countryEntity, $request->getContent());
        return new JsonResponse([
            'message' => 'Country updated successfully',
            'data' => $data,
        ]);
    }

    #[Route('/{country}', methods: ['DELETE'], requirements: ['country' => '\d+'])]
    public function deleteCountry(Country $countryEntity): JsonResponse
    {
        $this->countryService->deleteCountry($countryEntity);
        return new JsonResponse([
            'message' => 'Country deleted successfully',
            'data' => null,
        ]);
    }


}
