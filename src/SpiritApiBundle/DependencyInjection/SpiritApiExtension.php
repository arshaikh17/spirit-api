<?php

namespace Edcoms\SpiritApiBundle\DependencyInjection;

use Edcoms\SpiritApiBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Extension used to load the API key and URL from the configuration into the API caller service.
 * This allows any API call to be made automatically without having to deal with the basic configuration.
 * The API caller service is also defined in this extension.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class SpiritApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $options = isset($config['options']) ? $config['options'] : [];

        $this->loadSpiritApiHelpers($container, $options);
        $this->loadSynchronizer($container, $options);

        // set the configuration arguments for the model normalizer.
        $normalizerDef = $container->getDefinition('spirit_api.model_normalizer');
        $normalizerDef->setArguments([new Reference('annotation_reader'), $config['model_defaults']]);

        // add the configuration arguments into the API caller definition.
        $callerDef = $container->getDefinition('spirit_api.caller');
        $callerDef->setArguments([
            new Reference('event_dispatcher'),
            $config['key'],
            $config['url'],
            $config['version'],
            $config['prefix'],
            $options
        ]);

        $collectorDef = $container->getDefinition('spirit_api.call_collector');
        $collectorDef->setArguments(array_merge($collectorDef->getArguments(), [
            $config['key']
        ]));

        $pingCommandDef = $container->getDefinition('spirit_api.command.ping');
        $pingCommandDef->setArguments(array_merge($pingCommandDef->getArguments(), [
            $config['url']
        ]));

        $organisationHelperRef = $container->getDefinition('spirit_api.organisation');
        $organisationHelperRef->addMethodCall('setUserDefinedFields', [$config['user_defined_fields']['organisation']]);
    }

    /**
     * Retrieves the value from '$options' by the key of '$optionName'.
     * If the value does exist in '$options', it is removed.
     * Otherwise, the value of '$optionDefaultValue' is returned as a fallback.
     *
     * @param   string  $optionName          Name of the option to retrieve.
     * @param   mixed   $optionDefaultValue  Default value of the option return of it doesn't exist.
     * @param   array   $options             The options collection.
     *
     * @return  mixed                        The value of the option.
     */
    protected function getValueFromOptions(string $optionName, $optionDefaultValue, array &$options) {
        // set value as default first.
        $optionValue = $optionDefaultValue;

        if (isset($options[$optionName])) {
            $optionValue = $options[$optionName];

            // remove from the 'options' array as this won't be used anywhere else.
            unset($options[$optionName]);
        }

        return $optionValue;
    }

    private function loadSpiritApiHelpers(ContainerBuilder $container, array &$options)
    {
        $throwExceptionOnError = $this->getValueFromOptions('throw_exception_on_error', true, $options);

        $helpersDef = $container->getDefinition('spirit_api.helpers');
        $arguments = $helpersDef->getArguments();
        $arguments[2] = $throwExceptionOnError;
        $helpersDef->setArguments($arguments);

        $taggedServices = $container->findTaggedServiceIds('spirit_api.helper');
        $defaultHelperArguments = [
            new Reference('spirit_api.caller'),
            new Reference('spirit_api.model_mapper'),
            new Reference('spirit_api.model_normalizer'),
            $throwExceptionOnError
        ];

        foreach ($taggedServices as $id => $tags) {
            $optionsId = explode('.', $id);
            $optionsId = $optionsId[count($optionsId) - 1];
            $helperArguments = $defaultHelperArguments;

            // add the specific options for the current helper.
            if (isset($options[$optionsId])) {
                $helperArguments[] = $options[$optionsId];
            }

            $def = $container->getDefinition($id);
            $def->setArguments($helperArguments);

            $helperName = null;

            if (!empty($tags) && isset($tags[0]['helper_name'])) {
                $helperName = $tags[0]['helper_name'];
            } else {
                $idParts = explode('.', $id);

                $helperName = end($idParts);
            }

            $helpersDef->addMethodCall('addHelper', [new Reference($id), $helperName]);
        }
    }

    private function loadSynchronizer(ContainerBuilder $container, array &$options)
    {
        $maxAutoSync = $this->getValueFromOptions('max_auto_sync', 1, $options);
        $syncExpiry = $this->getValueFromOptions('sync_expiry', 1, $options);
        $def = $container->getDefinition('spirit_api.synchronizer');

        $arguments = $def->getArguments();
        $arguments[2] = $syncExpiry;
        $arguments[3] = $maxAutoSync;

        $def->setArguments($arguments);
    }
}
