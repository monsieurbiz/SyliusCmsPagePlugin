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
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class PageRepository extends EntityRepository implements PageRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode)
        ;
    }

    public function existsOneByChannelAndSlug(ChannelInterface $channel, ?string $locale, string $slug, array $excludedPages = []): bool
    {
        $queryBuilder = $this->createQueryBuilderExistOne($channel, $locale, $slug);
        if (!empty($excludedPages)) {
            $queryBuilder
                ->andWhere('p.id NOT IN (:excludedPages)')
                ->setParameter('excludedPages', $excludedPages)
            ;
        }

        $count = (int) $queryBuilder
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $count > 0;
    }

    public function existsOneEnabledByChannelAndSlug(ChannelInterface $channel, ?string $locale, string $slug, DateTimeInterface $dateTime): bool
    {
        $queryBuilder = $this->createQueryBuilderExistOne($channel, $locale, $slug);
        $queryBuilder
            ->andWhere('p.enabled = true')
            ->andWhere('p.publishAt IS NULL OR p.publishAt <= :now')
            ->andWhere('p.unpublishAt IS NULL OR p.unpublishAt >= :now')
            ->setParameter('now', $dateTime)
        ;

        $count = (int) $queryBuilder
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $count > 0;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneEnabledBySlugAndChannelCode(string $slug, string $localeCode, string $channelCode, DateTimeInterface $dateTime): ?PageInterface
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.translations', 'translation')
            ->innerJoin('p.channels', 'channels')
            ->where('translation.locale = :localeCode')
            ->andWhere('translation.slug = :slug')
            ->andWhere('channels.code = :channelCode')
            ->andWhere('p.enabled = true')
            ->andWhere('p.publishAt IS NULL OR p.publishAt <= :now')
            ->andWhere('p.unpublishAt IS NULL OR p.unpublishAt >= :now')
            ->setParameter('now', $dateTime)
            ->setParameter('localeCode', $localeCode)
            ->setParameter('slug', $slug)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function createQueryBuilderExistOne(ChannelInterface $channel, ?string $locale, string $slug): QueryBuilder
    {
        return $this
            ->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->innerJoin('p.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere('translation.slug = :slug')
            ->andWhere(':channel MEMBER OF p.channels')
            ->andWhere('p.enabled = true')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale)
            ->setParameter('slug', $slug)
        ;
    }
}
