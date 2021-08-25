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

namespace MonsieurBiz\SyliusCmsPagePlugin\Fixture\Factory;

use MonsieurBiz\SyliusCmsPagePlugin\Entity\BlockInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\BlockTranslationInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockFixtureFactory extends AbstractExampleFactory implements BlockFixtureFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $blockFactory;

    /**
     * @var FactoryInterface
     */
    private $blockTranslationFactory;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /** @var SlugGeneratorInterface */
    private $slugGenerator;

    /** @var \Faker\Generator */
    private $faker;

    /** @var RepositoryInterface */
    private $localeRepository;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    /**
     * @param FactoryInterface $blockFactory
     * @param FactoryInterface $blockTranslationFactory
     * @param SlugGeneratorInterface $slugGenerator
     * @param ChannelRepositoryInterface $channelRepository
     * @param RepositoryInterface $localeRepository
     */
    public function __construct(
        FactoryInterface $blockFactory,
        FactoryInterface $blockTranslationFactory,
        SlugGeneratorInterface $slugGenerator,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockTranslationFactory = $blockTranslationFactory;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;

        $this->slugGenerator = $slugGenerator;
        $this->faker = \Faker\Factory::create();

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = []): BlockInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var BlockInterface $block */
        $block = $this->blockFactory->createNew();
        $block->setEnabled($options['enabled']);
        $block->setCode($options['code']);
        $block->setTitle($options['title']);

        foreach ($options['channels'] as $channel) {
            $block->addChannel($channel);
        }

        $this->createTranslations($block, $options);

        return $block;
    }

    /**
     * @param BlockInterface $block
     * @param array $options
     */
    private function createTranslations(BlockInterface $block, array $options): void
    {
        foreach ($options['translations'] as $localeCode => $translation) {
            /** @var BlockTranslationInterface $blockTranslation */
            $blockTranslation = $this->blockTranslationFactory->createNew();
            $blockTranslation->setLocale($localeCode);
            $blockTranslation->setContent($translation['content']);

            $block->addTranslation($blockTranslation);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('enabled', function(Options $options): bool {
                return $this->faker->boolean(80);
            })
            ->setDefault('title', function(Options $options): string {
                return ucfirst($this->faker->sentence(3, true));
            })
            ->setDefault('code', function(Options $options): string {
                return $this->slugGenerator->generate($this->faker->sentence(2, true));
            })
            ->setDefault('translations', function(OptionsResolver $translationResolver): void {
                $translationResolver->setDefaults($this->configureDefaultTranslations());
            })
            ->setDefault('channels', LazyOption::all($this->channelRepository))
            ->setAllowedTypes('channels', 'array')
            ->setNormalizer('channels', LazyOption::findBy($this->channelRepository, 'code'))
        ;
    }

    /**
     * @return array
     */
    private function configureDefaultTranslations(): array
    {
        $translations = [];
        $locales = $this->localeRepository->findAll();
        /** @var LocaleInterface $locale */
        foreach ($locales as $locale) {
            $translations[$locale->getCode()] = [
                'content' => $this->faker->paragraphs(3, true)
            ];
        }

        return $translations;
    }
}
