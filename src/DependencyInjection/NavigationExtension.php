<?php
namespace Iterica\NavigationBundle\DependencyInjection;

use Iterica\Navigation\Navigation;
use Iterica\NavigationBundle\Extension\SecurityExtension;
use Iterica\NavigationBundle\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
            $this->addRoutingExtension();
        }

        if ($config['extensions']['security'] === true){
            $this->addSecurityExtension();
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRoutingExtension(ContainerBuilder $container){
        $container->getDefinition(RoutingExtension::class)
            ->setArguments([
                $container->getDefinition(UrlGeneratorInterface::class),
            ])
            ->addTag('navigation.extension');
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addSecurityExtension(ContainerBuilder $container){
        $container->getDefinition(SecurityExtension::class)
            ->setArguments([
                $container->getDefinition(TokenStorageInterface::class),
                $container->getDefinition(AuthorizationCheckerInterface::class),
                $container->getDefinition(RoleHierarchyInterface::class),
            ])
            ->addTag('navigation.extension');
    }
}
