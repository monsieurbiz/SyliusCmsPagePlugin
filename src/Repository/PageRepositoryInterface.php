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

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

use DateTimeInterface;
use Doctrine\ORM\QueryBuilder;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface PageRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder;

    public function existsOneByChannelAndSlug(ChannelInterface $channel, ?string $locale, string $slug, array $excludedPages = []): bool;

    public function existsOneEnabledByChannelAndSlug(ChannelInterface $channel, ?string $locale, string $slug, DateTimeInterface $dateTime): bool;

    public function findOneEnabledBySlugAndChannelCode(string $slug, string $localeCode, string $channelCode, DateTimeInterface $dateTime): ?PageInterface;
}
