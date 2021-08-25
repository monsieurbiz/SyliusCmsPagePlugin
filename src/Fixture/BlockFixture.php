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

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Fixture\Factory\BlockFixtureFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BlockFixture extends AbstractResourceFixture
{
    /**
     * @param EntityManagerInterface $blockManager
     * @param BlockFixtureFactoryInterface $exampleFactory
     */
    public function __construct(EntityManagerInterface $blockManager, BlockFixtureFactoryInterface $exampleFactory)
    {
        parent::__construct($blockManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'monsieurbiz_cms_block';
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
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->arrayNode('channels')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('translations')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('content')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
