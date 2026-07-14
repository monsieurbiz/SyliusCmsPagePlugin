<?php

namespace MonsieurBiz\SyliusCmsPagePlugin\Twig\Component;

use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageTranslationInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\TwigHooks\Twig\Component\HookableComponentTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
final class PageLink
{
    use HookableComponentTrait;

    public string $pageCode;

    public ?string $locale = null;

    public int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH;

    public function __construct(
        protected LocaleContextInterface $localeContext,
        protected ChannelContextInterface $channelContext,
        protected PageRepositoryInterface $pageRepository,
        protected UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[ExposeInTemplate('href')]
    public function getHref(): string
    {
        $page = $this->getPage();
        if (!($page instanceof PageInterface)) {
            return '';
        }
        $translation = $this->getPageTranslation($page);
        if (null === $translation) {
            return '';
        }
        $url = $this->urlGenerator->generate(
            'monsieurbiz_cms_page_show',
            [
                '_locale' => $translation->getLocale() ?? $this->resolveLocaleCode(),
                'slug' => $translation->getSlug(),
            ],
            $this->referenceType
        );
        return $url;
    }

    #[ExposeInTemplate(name: 'page_title')]
    public function getPageTitle(): string
    {
        $page = $this->getPage();
        if (!($page instanceof PageInterface)) {
            return '';
        }
        $translation = $this->getPageTranslation($page);

        return $translation?->getTitle() ?? '';
    }

    private function getPage(): ?PageInterface
    {
        $channel = $this->channelContext->getChannel();
        $now = new \DateTime();

        $lookupLocaleCode = $channel->getDefaultLocale()?->getCode() ?? $this->resolveLocaleCode();

        return $this->pageRepository->findOneEnabledAndPublishedByPageCodeAndChannelCode(
            $this->pageCode,
            $lookupLocaleCode,
            $channel,
            $now
        );
    }

    private function getPageTranslation(PageInterface $page): ?PageTranslationInterface
    {
        $translations = $page->getTranslations();

        $preferredTranslation = $translations->get($this->resolveLocaleCode());
        if ($preferredTranslation instanceof PageTranslationInterface) {
            return $preferredTranslation;
        }

        $channelDefaultLocaleCode = $this->channelContext->getChannel()->getDefaultLocale()?->getCode();
        if (null !== $channelDefaultLocaleCode) {
            $channelDefaultTranslation = $translations->get($channelDefaultLocaleCode);
            if ($channelDefaultTranslation instanceof PageTranslationInterface) {
                return $channelDefaultTranslation;
            }
        }

        $fallbackTranslation = $page->getTranslation();

        return $fallbackTranslation instanceof PageTranslationInterface ? $fallbackTranslation : null;
    }

    private function resolveLocaleCode(): string
    {
          return $this->locale ?? $this->localeContext->getLocaleCode();
    }
}
