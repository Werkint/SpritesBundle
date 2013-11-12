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
    protected $alias;

    /**
     * @param string $alias
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        // @formatter:off
        $treeBuilder
            ->root($this->alias)
            ->children()
                ->scalarNode('dir')->isRequired()->end()
                ->scalarNode('path')->isRequired()->end()
                ->scalarNode('styles')->isRequired()->end()
                ->scalarNode('namespace')->defaultValue('global-sprite')->end()
                ->scalarNode('defaultsize')->defaultValue('100')->end()
                ->arrayNode('sizes')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end()
            ->end()
        ;
        // @formatter:on

        return $treeBuilder;
    }

}
