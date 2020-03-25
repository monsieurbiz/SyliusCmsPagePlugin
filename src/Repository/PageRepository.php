<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository implements PageRepositoryInterface
{
    /**
     * @param string $localeCode
     * @return QueryBuilder
     */
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode)
        ;
    }

    /**
     * @param string $slug
     * @param string $localeCode
     * @param string $channelCode
     * @return PageInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneEnabledBySlugAndChannelCode(string $slug, string $localeCode, string $channelCode): ?PageInterface
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.translations', 'translation')
            ->innerJoin('p.channels', 'channels')
            ->where('translation.locale = :localeCode')
            ->andWhere('translation.slug = :slug')
            ->andWhere('channels.code = :channelCode')
            ->andWhere('p.enabled = true')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('slug', $slug)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
