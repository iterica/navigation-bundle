<?php

namespace Iterica\NavigationBundle\DependencyInjection;

use Iterica\Navigation\Navigation;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NavigationExtension extends Extension
{
    protected $tree;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $config = [];
        foreach ($configs as $conf) {
            $config = array_merge_recursive($config, $conf);
        }

        $container->getDefinition(Navigation::class)->addMethodCall('configure', [$config]);
    }
}
