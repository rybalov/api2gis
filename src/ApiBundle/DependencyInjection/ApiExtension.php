<?php

namespace ApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * ApiExtension.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class ApiExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach (['services'] as $configFile) {
            $loader->load(sprintf('%s.yml', $configFile));
        }
    }

    /**
     * @inheritdoc
     */
    public function getAlias()
    {
        return 'api_bundle';
    }
}
