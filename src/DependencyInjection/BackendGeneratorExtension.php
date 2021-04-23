<?php


namespace BackendGenerator\Bundle\BackendGeneratorBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BackendGeneratorExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');
        $loader->load('routes.yaml');

        $packageLoader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config/packages')
        );

        $packageLoader->load("api_platform.yaml");
    }
}