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
     * PageSlugConditionChecker constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     * @param ChannelContextInterface $channelContext
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext
    ) {
        $this->pageRepository = $pageRepository;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function isPageSlug(string $slug): bool
    {
        try {
            return $this->pageRepository->existsOneByChannelAndSlug(
                $this->channelContext->getChannel(),
                $this->localeContext->getLocaleCode(),
                $slug
            );
        } catch (ChannelNotFoundException $channelNotFoundException) {
            return false;
        }
    }
}
