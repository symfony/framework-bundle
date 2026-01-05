<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Tests\CacheWarmer;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\RouterCacheWarmer;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\RouterInterface;

class RouterCacheWarmerTest extends TestCase
{
    public function testWarmUpWithWarmebleInterface()
    {
        $container = new Container();

        $routerMock = $this->createStub(testRouterInterfaceWithWarmableInterface::class);
        $container->set('router', $routerMock);
        $routerCacheWarmer = new RouterCacheWarmer($container);

        $routerCacheWarmer->warmUp('/tmp');
        $routerMock->expects($this->any())->method('warmUp')->with('/tmp')->willReturn([]);
        $this->addToAssertionCount(1);
    }

    public function testWarmUpWithoutWarmebleInterface()
    {
        $container = new Container();

        $routerMock = $this->createStub(testRouterInterfaceWithoutWarmableInterface::class);
        $container->set('router', $routerMock);
        $routerCacheWarmer = new RouterCacheWarmer($container);
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('cannot be warmed up because it does not implement "Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface"');
        $routerCacheWarmer->warmUp('/tmp');
    }
}

interface testRouterInterfaceWithWarmableInterface extends RouterInterface, WarmableInterface
{
}

interface testRouterInterfaceWithoutWarmableInterface extends RouterInterface
{
}
