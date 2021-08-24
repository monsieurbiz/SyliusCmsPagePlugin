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

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\BlockInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class BlockRepository extends EntityRepository implements BlockRepositoryInterface
{
    /**
     * @throws NonUniqueResultException
     */
    public function findOneEnabledByBlockCodeAndChannelCode(string $blockCode, string $localeCode, string $channelCode): ?BlockInterface
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.translations', 'translation')
            ->innerJoin('b.channels', 'channels')
            ->where('translation.locale = :localeCode')
            ->andWhere('b.code = :blockCode')
            ->andWhere('channels.code = :channelCode')
            ->andWhere('b.enabled = true')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('blockCode', $blockCode)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
