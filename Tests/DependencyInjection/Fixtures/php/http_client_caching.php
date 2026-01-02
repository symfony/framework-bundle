<?php

$container->loadFromExtension('framework', [
    'http_client' => [
        'default_options' => [
            'headers' => ['X-powered' => 'PHP'],
            'caching' => [
                'cache_pool' => 'foo',
                'shared' => false,
                'max_ttl' => 2,
                'ttl_buffer' => 200,
            ],
        ],
        'scoped_clients' => [
            'bar' => [
                'base_uri' => 'http://example.com',
                'caching' => ['cache_pool' => 'baz'],
            ],
        ],
    ],
]);
