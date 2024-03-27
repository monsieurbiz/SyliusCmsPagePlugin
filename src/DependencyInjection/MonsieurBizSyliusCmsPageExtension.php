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

namespace MonsieurBiz\SyliusCmsPagePlugin\DependencyInjection;

use MonsieurBiz\SyliusCmsPagePlugin\Form\Type\PageType;
use MonsieurBiz\SyliusPlusAdapterPlugin\DependencyInjection\SyliusPlusCompatibilityTrait;
use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class MonsieurBizSyliusCmsPageExtension extends Extension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    use SyliusPlusCompatibilityTrait;

    /**
     * @inheritdoc
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $this->enabledFilteredChannelChoiceType($container, ['page' => PageType::class]);
    }

    /**
     * @inheritdoc
     */
    public function getAlias(): string
    {
        return str_replace('monsieur_biz', 'monsieurbiz', parent::getAlias());
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
        $this->prependRestrictedResources($container, ['page']);
        $this->replaceInGridOriginalQueryBuilderWithChannelRestrictedQueryBuilder(
            $container,
            'monsieurbiz_cms_page',
            '%monsieurbiz_cms_page.model.page.class%',
            "expr:service('monsieurbiz_cms_page.repository.page').createListQueryBuilder('%locale%')"
        );
    }

    protected function getMigrationsNamespace(): string
    {
        return 'MonsieurBiz\SyliusCmsPagePlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@MonsieurBizSyliusCmsPagePlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}
