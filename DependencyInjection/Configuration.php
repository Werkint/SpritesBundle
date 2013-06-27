<?php
namespace Werkint\Bundle\SpritesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
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
            ->scalarNode('styles')->end();

        $rootNode->end();
        return $treeBuilder;
    }
}