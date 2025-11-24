<?php

$container->loadFromExtension('framework', [
    'annotations' => false,
    'http_method_override' => false,
    'handle_all_throwables' => true,
    'php_errors' => ['log' => true],
    'enabled_locales' => ['es'],
    'translator' => [
        'providers' => [
            'foo_provider' => [
                'locales' => ['en', 'fr'],
            ],
            'bar_provider' => [
                'locales' => ['de', 'pl'],
            ]
        ]
    ],
]);
