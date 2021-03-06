<?php

namespace Getunik\BleedHd\AssessmentDataBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GetunikBleedHdAssessmentDataExtension extends Extension
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

        $container->setParameter('getunik_bleed_hd_assessment_data.version', $config['version']);
        $container->setParameter('getunik_bleed_hd_assessment_data.version_allow_git', $config['version_allow_git']);
        $container->setParameter('getunik_bleed_hd_assessment_data.settings', $config['settings']);
    }
}
