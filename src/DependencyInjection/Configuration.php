<?php

namespace Playtini\LoggerExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('service_logger_extra');
        
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('processor')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('additions')
                            ->info('Key => value variables that will be set in the [extra] sections of each log message')
                            ->useAttributeAsKey('key')
                            ->normalizeKeys(false)
                            ->prototype('scalar')
                                ->info('Key value')
                                ->isRequired()
                                ->example('value')
                            ->end()
                        ->end()        
                    ->end()
                ->end()
                ->arrayNode('logger')
                    ->addDefaultsIfNotSet()
                    ->children()
//                        ->scalarNode('on_request')->defaultFalse()->end()
//                        ->scalarNode('on_response')->defaultFalse()->end()
                        ->scalarNode('on_command')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}