<?php
namespace Iterica\NavigationBundle\DependencyInjection;

use Iterica\Navigation\Navigation;
use Iterica\NavigationBundle\Extension\RouteExtension;
use Iterica\NavigationBundle\Extension\SecurityExtension;
use Iterica\NavigationBundle\Extension\TranslationExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

final class NavigationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');;

        $container->getDefinition(Navigation::class)->addMethodCall('configureScopes', [$config['scope']]);

        if ($config['extensions']['routing'] === true) {
            $this->addRoutingExtension($container);
        }

        if ($config['extensions']['security'] === true){
            $this->addSecurityExtension($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRoutingExtension(ContainerBuilder $container){
        $container->getDefinition(RouteExtension::class)
            ->setAutowired(true)
            ->addTag('navigation.extension');
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addSecurityExtension(ContainerBuilder $container){
        $container->
        $container->getDefinition(SecurityExtension::class)
            ->setAutowired(true)
            ->addTag('navigation.extension');
    }
}
