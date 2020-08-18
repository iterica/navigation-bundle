<?php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Iterica\Navigation\Extension\NavigationExtensionInterface;
use Iterica\Navigation\Navigation;
use Iterica\NavigationBundle\Extension\RouteExtension;
use Iterica\NavigationBundle\Extension\SecurityExtension;
use Iterica\NavigationBundle\Extension\TranslationExtension;
use Iterica\NavigationBundle\Service\ConfigTranslationExtractor;
use Iterica\NavigationBundle\Templating\Twig\NavigationExtension;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->instanceof(NavigationExtensionInterface::class)
            ->tag('navigation.extension');

    $container->services()
        ->set(Navigation::class)
            ->args([
                service(EventDispatcherInterface::class),
                service(TranslatorInterface::class),
            ])
            ->call('setExtensions', [tagged_iterator('navigation.extension')])
            ->public();

    $container->services()
        ->set(NavigationExtension::class)
            ->args([
                service(Navigation::class),
                service(Environment::class),
            ])
            ->tag('twig.extension', [
                'alias' => 'navigation'
            ]);

    if (interface_exists(RouterInterface::class)) {
        $container->services()
            ->set(RouteExtension::class)
                ->args([
                    service(RouterInterface::class),
                    service(RequestStack::class),
                ])
                ->tag('navigation.extension');
    }

    if (interface_exists(AuthorizationCheckerInterface::class)) {
        $container->services()
            ->set(SecurityExtension::class)
                ->args([
                    service(TokenStorageInterface::class),
                    service(AuthorizationCheckerInterface::class),
                    service(RoleHierarchyInterface::class),
                ])
                ->tag('navigation.extension');
    }
};
