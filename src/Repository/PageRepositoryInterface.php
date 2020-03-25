<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface PageRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder;

    public function findOneEnabledBySlugAndChannelCode(string $slug, string $localeCode, string $channelCode): ?PageInterface;
}
