<?php

return [

    'default' => 'default',

    'documentations' => [

        'default' => [
            'api' => [
                'title' => 'Laravel API Documentation',
            ],

            'routes' => [
                /*
                 * Route for accessing the Swagger documentation interface.
                 */
                'api' => 'api/documentation',

                /*
                 * Middleware for protecting Swagger documentation route.
                 */
                'middleware' => [
                    'api' => [],
                    'asset' => [],
                    'docs' => [],
                    'oauth2_callback' => [],
                ],
            ],

            'paths' => [
                /*
                 * Absolute path to location where the swagger json file will be generated
                 */
                'docs' => storage_path('api-docs'),

                /*
                 * File name of the generated json documentation file
                 */
                'docs_json' => 'api-docs.json',

                /*
                 * Set to true as we are using external YAML files for the documentation.
                 */
                'docs_yaml' => true, // Change this to true to enable YAML generation.

                /*
                 * The base path for the API routes.
                 */
                'base' => env('L5_SWAGGER_BASE_PATH', null),

                /*
                 * The path where the main OpenAPI YAML file is located.
                 * Since you're using external YAML files, point this to your YAML directory.
                 */
                'annotations' => base_path('resources/swagger/openapi.yml'),

                /*
                 * List of directories containing additional annotations (if you have other external files).
                 * Since you're using YAML, you can leave this empty.
                 */
                'excludes' => [],
            ],

            'swagger_ui' => [
                'display_operation_id' => true,
                'default_model_expand_depth' => 2,
                'default_model_rendering' => 'model',
                'default_models_expand_depth' => 1,
                'doc_expansion' => 'none', // none | list | full
                'filter' => true, // Enables a search box
                'max_displayed_tags' => null,
                'show_extensions' => false,
                'show_common_extensions' => false,
            ],

            'security_definitions' => [
                /*
                 * Sanctum API Token Authentication.
                 */
                'sanctum' => [
                    'type' => 'apiKey',
                    'description' => 'Enter your Bearer token in the format (Bearer <token>)',
                    'name' => 'Authorization',
                    'in' => 'header',
                ],
            ],

            /*
             * Global headers applied to all endpoints.
             */
            'headers' => [
                'Content-Type' => 'application/json',
            ],

            /*
             * Custom operations configuration.
             */
            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost/api'),
            ],
        ],
    ],

    /*
     * Set this flag to `true` to run swagger generation every time your project is updated.
     */
    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

    /*
     * API version number.
     */
    'version' => env('APP_VERSION', '1.0.0'),

    /*
     * Supported OpenAPI spec version.
     */
    'swagger_version' => env('L5_SWAGGER_VERSION', '3.1.0'),

    /*
     * Specify any HTTP headers required for the Swagger docs access.
     */
    'headers' => [],

    /*
     * Middleware to apply on `api/docs` routes.
     */
    'middleware' => [
        'api' => [],
        'asset' => [],
        'docs' => [],
        'oauth2_callback' => [],
    ],

    /*
     * Authorization configurations.
     */
    'oauth' => [
        'client_id' => env('L5_SWAGGER_OAUTH_CLIENT_ID'),
        'client_secret' => env('L5_SWAGGER_OAUTH_CLIENT_SECRET'),
        'realm' => env('L5_SWAGGER_REALM'),
        'appName' => env('L5_SWAGGER_APP_NAME'),
        'scopeSeparator' => env('L5_SWAGGER_SCOPE_SEPARATOR', ' '),
        'additional_query_string_params' => [],
        'use_basic_auth_with_access_code_grant' => false,
    ],
];
