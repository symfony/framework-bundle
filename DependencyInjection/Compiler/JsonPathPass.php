<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\JsonPath\FunctionReturnType;

trigger_deprecation('symfony/framework-bundle', '8.2', 'The "%s" class is deprecated, use "%s" from the JsonPath component instead.', JsonPathPass::class, \Symfony\Component\JsonPath\DependencyInjection\JsonPathPass::class);

/**
 * Collects metadata (arity, return type) for custom JsonPath functions
 * and injects it into the json_path.crawler service.
 *
 * @deprecated since Symfony 8.2, use the JsonPathPass of the JsonPath component instead
 */
class JsonPathPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('json_path.crawler')) {
            return;
        }

        $metadata = [];
        foreach ($container->findTaggedServiceIds('json_path.function') as $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['name'])) {
                    continue;
                }

                $returnType = $attributes['return_type'] ?? null;

                $metadata[$attributes['name']] = [
                    'arity' => $attributes['arity'] ?? null,
                    'return_type' => \is_string($returnType) ? FunctionReturnType::from($returnType) : $returnType,
                ];
            }
        }

        $container->getDefinition('json_path.crawler')->setArgument(1, $metadata);
    }
}
