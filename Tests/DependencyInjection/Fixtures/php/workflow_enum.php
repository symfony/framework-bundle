<?php

use Symfony\Bundle\FrameworkBundle\Tests\DependencyInjection\Fixtures\Workflow\Places;
use Symfony\Bundle\FrameworkBundle\Tests\DependencyInjection\FrameworkExtensionTestCase;

$container->loadFromExtension('framework', [
    'workflows' => [
        'enum' => [
            'supports' => [
                FrameworkExtensionTestCase::class,
            ],
            'places' => Places::cases(),
            'transitions' => [
                'one' => [
                    'from' => Places::A->value,
                    'to' => Places::B->value,
                ],
                'two' => [
                    'from' => Places::B->value,
                    'to' => Places::C->value,
                ],
            ],
        ]
    ],
]);
