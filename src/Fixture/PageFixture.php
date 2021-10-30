<?php

/*
 * This file is part of Monsieur Biz' Cms Page plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Fixture\Factory\PageFixtureFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class PageFixture extends AbstractResourceFixture
{
    /**
     * @param EntityManagerInterface $pageManager
     * @param PageFixtureFactoryInterface $exampleFactory
     */
    public function __construct(EntityManagerInterface $pageManager, PageFixtureFactoryInterface $exampleFactory)
    {
        parent::__construct($pageManager, $exampleFactory);
    }

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
        /** @phpstan-ignore-next-line */
        $resourceNode
            ->children()
                ->booleanNode('enabled')->end()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->arrayNode('channels')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('translations')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('title')->cannotBeEmpty()->end()
                            ->scalarNode('slug')->cannotBeEmpty()->end()
                            ->scalarNode('content')->cannotBeEmpty()->end()
                            ->scalarNode('metaTitle')->end()
                            ->scalarNode('metaDescription')->end()
                            ->scalarNode('metaKeywords')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
