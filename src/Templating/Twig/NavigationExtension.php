<?php
namespace Iterica\NavigationBundle\Templating\Twig;

use Exception;

use Iterica\Navigation\Navigation;
use Iterica\Navigation\Node\Node;
use Iterica\Navigation\Node\ScopeNode;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    /** @var Navigation $navigation */
    protected $navigation;

    /** @var Environment $twig */
    protected $twig;

    /** @var string $navigationTemplate */
    protected $navigationTemplate = 'NavigationBundle::navigation.html.twig';

    /** @var string $breadcrumbsTemplate */
    protected $breadcrumbsTemplate = 'NavigationBundle::breadcrumbs.html.twig';

    /** @var string $inlinenodesTemplate */
    protected $inlinenodesTemplate = 'NavigationBundle::inlinenodes.html.twig';

    /**
     * NavigationHelper constructor.
     * @param Navigation $navigation
     * @param Environment $twig
     */
    public function __construct(Navigation $navigation, Environment $twig)
    {
        $this->navigation = $navigation;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('navigation', [$this, 'navigation'], ['is_safe'=>['html']]),
            new TwigFunction('breadcrumbs', [$this, 'breadcrumbs'], ['is_safe'=>['html']]),
            new TwigFunction('inlinenodes', [$this, 'inlinenodes'], ['is_safe'=>['html']]),
            new TwigFunction('navigationScope', [$this, 'getScope'])
        ];
    }

    /**
     * @param string $scope
     * @return ScopeNode
     * @throws Exception
     */
    public function getScope(string $scope): ScopeNode
    {
        return $this->navigation->getScope($scope);
    }

    /**
     * @param string $scope
     * @param string|null $template
     * @return string
     */
    public function navigation(string $scope, string $template = null)
    {
        $scopeNode = $this->navigation->getScope($scope);

        return $this->twig->render(
            $template ?? $this->navigationTemplate,
            ['navigation' => $this->navigation, 'scope' => $scopeNode]
        );
    }

    /**
     * @param string $scope
     * @param string|null $template
     * @return string
     */
    public function breadcrumbs(string $scope, string $template = null)
    {
        $scopeNode = $this->navigation->getScope($scope);

        return $this->twig->render(
            $template ?? $this->breadcrumbsTemplate,
            ['navigation' => $this->navigation, 'scope' => $scopeNode]
        );
    }

    /**
     * @param string $scope
     * @param string|null $template
     * @return string
     */
    public function inlinenodes(string $scope, string $template = null)
    {
        $scopeNode = $this->navigation->getScope($scope);

        return $this->twig->render(
            $template ?? $this->inlinenodesTemplate,
            ['navigation' => $this->navigation, 'scope' => $scopeNode]
        );
    }
}
