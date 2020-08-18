<?php
namespace Iterica\NavigationBundle;

use Iterica\Navigation\Extension\NavigationExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NavigationBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(NavigationExtensionInterface::class)
            ->addTag('navigation.extension');
    }
}
