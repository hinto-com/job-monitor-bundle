<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('hinto_job_monitor');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('connection')
            ->defaultValue('doctrine.dbal.default_connection')  // Default: servizio di connessione Doctrine standard
            ->info('Il servizio DBAL da usare per le query (es. @doctrine.dbal.my_connection)')
            ->end()
            ->end();

        return $treeBuilder;
    }
}
