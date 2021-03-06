<?php

namespace Getunik\BleedHd\AssessmentDataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('getunik_bleed_hd_assessment_data');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('version')->end()
                ->scalarNode('version_allow_git')->end()
                ->arrayNode('settings')
                    ->children()
                        ->append($this->getAllowedAssessmentTypesNode())
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    public function getAllowedAssessmentTypesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('allowed_assessment_types');

        $node
            ->isRequired()
            ->children()
                ->arrayNode('gvhd')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('bleeding')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
