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

namespace MonsieurBiz\SyliusCmsPagePlugin\Menu;

use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use MonsieurBiz\SyliusMenuPlugin\Provider\AbstractUrlProvider;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class PageUrlProvider extends AbstractUrlProvider
{
    public const PROVIDER_CODE = 'page';

    protected string $code = self::PROVIDER_CODE;

    protected string $icon = 'file alternate';

    protected int $priority = 40;

    public function __construct(
        RouterInterface $router,
        private PageRepositoryInterface $pageRepository,
    ) {
        parent::__construct($router);
    }

    protected function getResults(string $locale, string $search = ''): iterable
    {
        $queryBuilder = $this->pageRepository->createListQueryBuilder($locale)
            ->andWhere('o.enabled = :enabled')
            ->setParameter('enabled', true)
        ;

        if (!empty($search)) {
            $queryBuilder
                ->andWhere('translation.title LIKE :search OR translation.slug LIKE :search')
                ->setParameter('search', '%' . $search . '%')
            ;
        }

        $queryBuilder->setMaxResults($this->getMaxResults());

        /** @phpstan-ignore-next-line */
        return $queryBuilder->getQuery()->getResult();
    }

    protected function addItemFromResult(object $result, string $locale): void
    {
        Assert::isInstanceOf($result, PageInterface::class);
        /** @var PageInterface $result */
        $result->setCurrentLocale($locale);
        $this->addItem(
            (string) $result->getTitle(),
            $this->router->generate('monsieurbiz_cms_page_show', ['slug' => $result->getSlug(), '_locale' => $locale])
        );
    }
}
