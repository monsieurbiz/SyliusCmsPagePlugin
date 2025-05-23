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

namespace MonsieurBiz\SyliusCmsPagePlugin\Fixture\Factory;

use DateTime;
use Faker\Generator;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageTranslationInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageFixtureFactory extends AbstractExampleFactory implements PageFixtureFactoryInterface
{
    private OptionsResolver $optionsResolver;

    private Generator $faker;

    public function __construct(
        private FactoryInterface $pageFactory,
        private FactoryInterface $pageTranslationFactory,
        private SlugGeneratorInterface $slugGenerator,
        private ChannelRepositoryInterface $channelRepository,
        private RepositoryInterface $localeRepository
    ) {
        $this->faker = \Faker\Factory::create();

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): PageInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var PageInterface $page */
        $page = $this->pageFactory->createNew();
        $page->setEnabled($options['enabled']);
        $page->setCode($options['code']);

        $page->setPublishAt($options['publish_at']);
        $page->setUnpublishAt($options['unpublish_at']);

        foreach ($options['channels'] as $channel) {
            $page->addChannel($channel);
        }

        $this->createTranslations($page, $options);

        return $page;
    }

    private function createTranslations(PageInterface $page, array $options): void
    {
        foreach ($options['translations'] as $localeCode => $translation) {
            /** @var PageTranslationInterface $pageTranslation */
            $pageTranslation = $this->pageTranslationFactory->createNew();
            $pageTranslation->setLocale($localeCode);
            $pageTranslation->setTitle($translation['title']);
            $pageTranslation->setContent($translation['content']);
            $pageTranslation->setSlug($translation['slug']);
            $pageTranslation->setMetaTitle($translation['metaTitle']);
            $pageTranslation->setMetaDescription($translation['metaDescription']);
            $pageTranslation->setMetaKeywords($translation['metaKeywords']);

            $page->addTranslation($pageTranslation);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('enabled', function (Options $options): bool {
                return $this->faker->boolean(80);
            })
            ->setDefault('code', function (Options $options): string {
                return $this->slugGenerator->generate($this->faker->sentence(2, true));
            })
            ->setDefault('translations', function (OptionsResolver $translationResolver): void {
                $translationResolver->setDefaults($this->configureDefaultTranslations());
            })
            ->setDefault('publish_at', null)
            ->setNormalizer('publish_at', function (Options $options, $value): ?DateTime {
                return null === $value ? null : new DateTime($value);
            })
            ->setDefault('unpublish_at', null)
            ->setNormalizer('unpublish_at', function (Options $options, $value): ?DateTime {
                return null === $value ? null : new DateTime($value);
            })
            ->setDefault('channels', LazyOption::all($this->channelRepository))
            ->setAllowedTypes('channels', 'array')
            ->setNormalizer('channels', LazyOption::findBy($this->channelRepository, 'code'))
        ;
    }

    private function configureDefaultTranslations(): array
    {
        $translations = [];
        $locales = $this->localeRepository->findAll();
        /** @var LocaleInterface $locale */
        foreach ($locales as $locale) {
            $title = ucfirst($this->faker->sentence(3, true));
            $translations[$locale->getCode()] = [
                'title' => $title,
                'content' => $this->faker->paragraphs(3, true),
                'slug' => $this->slugGenerator->generate($title),
                'metaTitle' => $title,
                'metaDescription' => $this->faker->paragraph,
                'metaKeywords' => $this->faker->sentence(10, true),
            ];
        }

        return $translations;
    }
}
