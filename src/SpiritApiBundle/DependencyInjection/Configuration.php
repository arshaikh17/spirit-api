<?php

namespace Edcoms\SpiritApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * A specification on how the configuration of this bundle should be set up.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('spirit_api');

        $rootNode
            ->children()
                // this will be the API key used to authenticate SPIRIT API call.
                ->scalarNode('key')
                    ->isRequired()
                    ->end()
                // this will be the base URL of the SPIRIT API.
                ->scalarNode('url')
                    ->defaultValue('http://uat-phobos.education.co.uk/Service')
                    ->end()
                ->scalarNode('version')
                    ->isRequired()
                    ->end()
                // this will be added onto the end of the base URL with each request.
                ->scalarNode('prefix')
                    ->defaultValue('/API')
                    ->end()
                // these values will be automatically added if the field has not been specified in a Model object.
                ->arrayNode('model_defaults')
                    ->prototype('array')
                        ->prototype('variable')
                            ->end()
                        ->end()
                    ->end()
                // this will be any HTTP request options.
                ->arrayNode('options')
                    ->children()
                        ->booleanNode('auto_sync')
                            ->defaultValue(true)
                            ->end()
                        ->booleanNode('connection_timeout')
                            ->defaultValue(10) // in seconds.
                            ->end()
                        ->integerNode('max_auto_sync')
                            ->defaultValue(1)
                            ->end()
                        ->arrayNode('organisation') // organisation specific options.
                            ->children()
                                ->integerNode('search_limit')
                                    ->defaultValue(0)
                                    ->end()
                                ->end()
                            ->end()
                        ->booleanNode('sync_expiry')
                            ->defaultValue(1) // in days.
                            ->end()
                        ->booleanNode('throw_exception_on_error')
                            ->defaultTrue()
                            ->end()
                        ->arrayNode('proxy')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('scheme')->defaultValue('http')->end()//accepted values: http or https
                                ->scalarNode('base_url')->defaultValue('')->end()//username:password@host:port
                            ->end()
                        ->end()
                    ->end()
            ->end()
            ->arrayNode('user_defined_fields')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('organisation')
                        ->defaultValue('Latitude,Longitude,PupilsOnRoll') // introduce default fields in order not to introduce breaking changes
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
