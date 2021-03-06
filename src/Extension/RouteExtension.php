<?php

namespace Iterica\NavigationBundle\Extension;

use Iterica\Navigation\Extension\AbstractNavigationExtension;
use Iterica\Navigation\Node\Node;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class RouteExtension extends AbstractNavigationExtension
{
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('route', null);
        $resolver->setAllowedTypes('route', ['null', 'string', 'array']);
    }

    public function processNode(Node $node): void
    {
        $params = [];
        $route = $node->getOption('route');

        if ($route !== null) {
            if (is_array($route)) {
                $params = array_merge($route[1], $params);
                $route = $route[0];
            }

            try {
                $node->setUrl($this->router->generate($route, $params));

                $request = $this->requestStack->getCurrentRequest();

                if ($request !== null) {
                    if ($route === $request->attributes->get('_route')) {
                        $node->setActive(true);
                    }
                }
            } catch (RouteNotFoundException $exception) {
                $node->setUrl('#');
            }
        }
    }
}
