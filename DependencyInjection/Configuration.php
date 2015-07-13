<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iphp\CoreBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('iphp_core')->children();


        $node->arrayNode('class')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('rubric')->defaultValue('Application\\Iphp\\CoreBundle\\Entity\\Rubric')->end()
                            ->scalarNode('block')->defaultValue('Application\\Iphp\\CoreBundle\\Entity\\Block')->end()
                            ->scalarNode('createupdateuser')->defaultValue('Application\\Sonata\\UserBundle\\Entity\\User')->end()
                        ->end()
                    ->end()
             ->booleanNode ('separate_admin_env')->defaultFalse()->end();
        return $treeBuilder;
    }
}
