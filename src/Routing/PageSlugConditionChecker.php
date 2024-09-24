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
use Sylius\Calendar\Provider\DateTimeProviderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class PageSlugConditionChecker
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var DateTimeProviderInterface
     */
    private $dateTimeProvider;

    /**
     * PageSlugConditionChecker constructor.
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        DateTimeProviderInterface $dateTimeProvider
    ) {
        $this->pageRepository = $pageRepository;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function isPageSlug(string $slug): bool
    {
        try {
            return $this->pageRepository->existsOneEnabledByChannelAndSlug(
                $this->channelContext->getChannel(),
                $this->localeContext->getLocaleCode(),
                $slug,
                $this->dateTimeProvider->now()
            );
        } catch (ChannelNotFoundException $channelNotFoundException) {
            return false;
        }
    }
}
