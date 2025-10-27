<?php

namespace MonsieurBiz\SyliusCmsPagePlugin\Twig\Component;

use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\TwigHooks\Twig\Component\HookableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
final class PageLink
{
    use HookableComponentTrait;

    public string $pageCode;

    public function __construct(
        protected LocaleContextInterface $localeContext,
        protected ChannelContextInterface $channelContext,
        protected PageRepositoryInterface $pageRepository,
    ) {
    }

    #[ExposeInTemplate('href')]
    public function getHref(): string
    {
        $page = $this->getPage();
        return $this->localeContext->getLocaleCode() . '/' . $page->getSlug();
    }

    #[ExposeInTemplate(name: 'page_title')]
    public function getPageTitle(): string
    {
        return $this->getPage()->getTitle();
    }

    private function getPage(): PageInterface
    {
        $currentLocaleCode = $this->localeContext->getLocaleCode();
        $channel = $this->channelContext->getChannel();
        $now = new \DateTime();
        return $this->pageRepository->findOneEnabledAndPublishedByPageCodeAndChannelCode(
            $this->pageCode,
            $currentLocaleCode,
            $channel,
            $now
        );
    }
}
