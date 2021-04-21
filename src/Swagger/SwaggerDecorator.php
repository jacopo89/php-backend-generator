<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\Swagger;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use BackendGenerator\Bundle\Model\Url;

final class SwaggerDecorator implements NormalizerInterface
{
    private NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs = $this->addSecurityDoc($docs);
        $docs = $this->addResourceListing($docs);

        return $docs;
    }

    private function addSecurityDoc(array $docs): array
    {
        $docs['components']['schemas']['Token'] = [
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'refresh_token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ]
            ],
        ];

        $docs['components']['schemas']['Credentials'] = [
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                ],
                'password' => [
                    'type' => 'string',
                ],
            ],
        ];

        $docs['components']['schemas']['RefreshToken'] = [
            'type' => 'object',
            'properties' => [
                'refresh_token' => [
                    'type' => 'string',
                ],
            ],
        ];

        $tokenDocumentation = [
            'paths' => [
                Url::AUTH => [
                    'post' => [
                        'tags' => ['Token'],
                        'operationId' => 'postCredentialsItem',
                        'summary' => 'Get JWT token to login.',
                        'requestBody' => [
                            'description' => 'Create new JWT Token',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Credentials',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get JWT token',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/Token',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                Url::REFRESH_TOKEN => [
                    'post' => [
                        'tags' => ['Refresh Token'],
                        'operationId' => 'postCredentialsItem',
                        'summary' => 'Refresh JWT Token.',
                        'requestBody' => [
                            'description' => 'Refresh JWT Token',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/RefreshToken',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get JWT token',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/Token',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

        ];

        return array_merge_recursive($tokenDocumentation, $docs);
    }

    private function addResourceListing(array $docs): array
    {
        $docs['components']['schemas']['ResourceListing'] = [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'string'
                ],
                'label' => [
                    'type' => 'string'
                ],
            ],
        ];

        $resourceListingsDocumentation = [
            'paths' => [
                Url::RESOURCE_LISTING => [
                    'get' => [
                        'tags' => ['ResourcesListing'],
                        'operationId' => 'resourcesListing',
                        'summary' => 'Get resources listing.',
                        "parameters" => [
                            [
                                "name" => "resource",
                                "in" => "path",
                                "required" => true,
                                "schema" => ["type" => "string"]
                            ]
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get resources listing',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            "type" => "array",
                                            '$ref' => '#/components/schemas/ResourceListing',
                                        ],
                                    ],
                                ],
                            ],
                            Response::HTTP_NOT_FOUND => [
                                "description" => "Resource not found"
                            ]
                        ],
                    ],
                ],
            ],
        ];

        return array_merge_recursive($resourceListingsDocumentation, $docs);
    }
}