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

namespace MonsieurBiz\SyliusCmsPagePlugin\Routing;

use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Request;

#[AsRoutingConditionService(alias: 'monsieurbiz.cms_page.route_checker')]
final class PageSlugConditionChecker
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private ChannelContextInterface $channelContext,
        private LocaleContextInterface $localeContext,
        private ClockInterface $clock,
    ) {
    }

    public function checkPageSlug(Request $request): bool
    {
        return $this->isPageSlug($this->prepareSlug($request->getPathInfo()));
    }

    public function isPageSlug(string $slug): bool
    {
        try {
            return $this->pageRepository->existsOneEnabledAndPublishedByChannelAndSlug(
                $this->channelContext->getChannel(),
                $this->localeContext->getLocaleCode(),
                $slug,
                $this->clock->now()
            );
        } catch (ChannelNotFoundException $channelNotFoundException) {
            return false;
        }
    }

    private function prepareSlug(string $slug): string
    {
        $slug = ltrim($slug, '/');
        $localeCode = $this->localeContext->getLocaleCode();

        if (false === strpos($slug, $localeCode)) {
            return $slug;
        }

        // Remove the locale code which is at the beginning of the slug
        return (string) preg_replace(\sprintf('/^%s\//', $localeCode), '', $slug);
    }
}
