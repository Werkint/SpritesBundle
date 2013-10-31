<?php
namespace Werkint\Bundle\SpritesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Configuration implements
    ConfigurationInterface
{

    private $alias;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->alias)->children();

        $rootNode
            ->scalarNode('dir')->end()
            ->scalarNode('path')->end()
            ->scalarNode('styles')->end()
            ->scalarNode('namespace')->defaultValue('global-sprite')->end()
            ->scalarNode('defaultsize')->defaultValue('100')->end();
        $rootNode
            ->arrayNode('sizes')
            ->useAttributeAsKey('name')
            ->prototype('scalar')
            ->end()
            ->end();

        $rootNode->end();
        return $treeBuilder;
    }
}