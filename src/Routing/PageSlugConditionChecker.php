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

namespace MonsieurBiz\SyliusCmsPagePlugin\Routing;

use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;

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
     * PageSlugConditionChecker constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     * @param ChannelContextInterface $channelContext
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->pageRepository = $pageRepository;
        $this->channelContext = $channelContext;
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function isPageSlug(string $locale, string $slug): bool
    {
        try {
            return $this->pageRepository->existsOneByChannelAndSlug(
                $this->channelContext->getChannel(),
                $locale,
                $slug
            );
        } catch (ChannelNotFoundException $channelNotFoundException) {
            return false;
        }
    }
}
