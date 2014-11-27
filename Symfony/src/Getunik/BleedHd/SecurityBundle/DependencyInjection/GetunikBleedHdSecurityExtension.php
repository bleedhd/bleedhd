<?php

namespace Getunik\BleedHd\SecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GetunikBleedHdSecurityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        //$container->setParameter('getunik_bleed_hd_security.auto_token_client.id', '42');
        $container->setParameter('getunik_bleed_hd_security.auto_token_client.id', $config['auto_token_client']['id']);
        $container->setParameter('getunik_bleed_hd_security.auto_token_client.secret', $config['auto_token_client']['secret']);
        $container->setParameter('getunik_bleed_hd_security.auto_token_client.target_path', $config['auto_token_client']['target_path']);
    }
}
