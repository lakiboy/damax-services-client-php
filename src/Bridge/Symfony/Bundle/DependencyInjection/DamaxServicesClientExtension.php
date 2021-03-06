<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Services\Client\Client;
use Damax\Services\Client\Configuration as ClientConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class DamaxServicesClientExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->configureClient($config, $container);
    }

    private function configureClient(array $config, ContainerBuilder $container): self
    {
        $container
            ->register(ClientConfiguration::class)
            ->addArgument($config['endpoint'])
            ->addArgument($config['api_key'])
        ;

        $container
            ->register(Client::class)
            ->setFactory([new Reference(ClientConfiguration::class), 'getClient'])
        ;

        return $this;
    }
}
