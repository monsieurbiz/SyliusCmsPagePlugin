<?php
declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Routing;

use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
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
        return $this->pageRepository->existsOneByChannelAndSlug(
            $this->channelContext->getChannel(),
            $this->localeContext->getLocaleCode(),
            $slug
        );
    }
}
