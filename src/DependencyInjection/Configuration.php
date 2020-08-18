<?php
namespace Iterica\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('navigation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
                ->arrayNode('extensions')
                    ->booleanPrototype('routing')->defaultTrue()->end()
                    ->booleanPrototype('security')->defaultTrue()->end()
                ->end()
                ->arrayNode('scope')
                    ->useAttributeAsKey('')
                    ->variablePrototype()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
