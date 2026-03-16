<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class HintoJobMonitorExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');  // Carica il file services.yaml

        // Processa la configurazione
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Imposta dinamicamente l'argomento per JobMonitorService
        $serviceDefinition = $container->getDefinition(\Hinto\Bundle\JobMonitorBundle\Service\JobMonitorService::class);
        $serviceDefinition->setArgument('$connection', new Reference($config['connection']));
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Opzionale: prepend per configurazioni di altri bundle, se necessario
    }
}
