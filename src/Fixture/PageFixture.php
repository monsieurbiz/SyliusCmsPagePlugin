<?php

/*
 * This file is part of Monsieur Biz' Cms Page plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class PageFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'monsieurbiz_cms_page';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->booleanNode('enabled')->end()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->arrayNode('channels')->scalarPrototype()->end()->end()
                ->arrayNode('translations')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('title')->cannotBeEmpty()->end()
                            ->scalarNode('slug')->cannotBeEmpty()->end()
                            ->scalarNode('content')->cannotBeEmpty()->end()
                            ->scalarNode('metaTitle')->cannotBeEmpty()->end()
                            ->scalarNode('metaDescription')->cannotBeEmpty()->end()
                            ->scalarNode('metaKeywords')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
