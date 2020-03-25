<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Fixture\Factory;

use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageTranslationInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageFixtureFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $pageFactory;

    /**
     * @var FactoryInterface
     */
    private $pageTranslationFactory;

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
     * @param FactoryInterface $pageFactory
     * @param FactoryInterface $pageTranslationFactory
     * @param SlugGeneratorInterface $slugGenerator
     * @param ChannelRepositoryInterface $channelRepository
     * @param RepositoryInterface $localeRepository
     */
    public function __construct(
        FactoryInterface $pageFactory,
        FactoryInterface $pageTranslationFactory,
        SlugGeneratorInterface $slugGenerator,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageTranslationFactory = $pageTranslationFactory;
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
    public function create(array $options = []): PageInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var PageInterface $page */
        $page = $this->pageFactory->createNew();
        $page->setEnabled($options['enabled']);
        $page->setCode($options['code']);

        foreach ($options['channels'] as $channel) {
            $page->addChannel($channel);
        }

        $this->createTranslations($page, $options);

        return $page;
    }

    /**
     * @param PageInterface $page
     * @param array $options
     */
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
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('enabled', function (Options $options): bool {
                return $this->faker->boolean(80);
            })
            ->setDefault('code', function (Options $options): string {
                return $this->slugGenerator->generate($this->faker->words(2, true));
            })
            ->setDefault('translations', function(OptionsResolver $translationResolver) {
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
            $title = ucfirst($this->faker->words(3, true));
            $translations[$locale->getCode()] = [
                'title' => $title,
                'content' => $this->faker->paragraphs(3, true),
                'slug' => $this->slugGenerator->generate($title),
                'metaTitle' => $title,
                'metaDescription' => $this->faker->paragraph,
                'metaKeywords' => $this->faker->words(10, true),
            ];
        }
        return $translations;
    }
}
