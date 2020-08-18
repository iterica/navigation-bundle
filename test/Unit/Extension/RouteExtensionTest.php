<?php
declare(strict_types=1);

namespace Iterica\NavigationBundle\Test\Unit\Extension;

use Iterica\Navigation\Node\Node;
use Iterica\NavigationBundle\Extension\RouteExtension;
use PHPUnit\Framework;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Router;

/**
 * @internal
 *
 * @covers \Iterica\NavigationBundle\Extension\RouteExtension
 */
final class RouteExtensionTest extends Framework\TestCase
{
    public function testInitializeRoutingExtension(): void
    {
        $router = $this->createMock(Router::class);
        $router->method('generate')->willReturn('/');
        $requestStack = $this->createMock(RequestStack::class);

        $routingExtension = new RouteExtension($router, $requestStack);

        $node = new Node('main', [
            'label' => 'bla',
            'route' => 'index'
        ], function (OptionsResolver $resolver) use ($routingExtension) {
            $routingExtension->configureOptions($resolver);
        });

        $routingExtension->processNode($node);

        parent::assertEquals($node->getUrl(), '/');
    }
}
