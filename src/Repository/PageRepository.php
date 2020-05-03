<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class PageRepository extends EntityRepository implements PageRepositoryInterface
{
    /**
     * @param string $localeCode
     *
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
     * @param ChannelInterface $channel
     * @param string|null $locale
     * @param string $slug
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function existsOneByChannelAndSlug(ChannelInterface $channel, ?string $locale, string $slug): bool
    {
        $count = (int) $this
            ->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->innerJoin('p.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere('translation.slug = :slug')
            ->andWhere(':channel MEMBER OF p.channels')
            ->andWhere('p.enabled = true')
            ->setParameter('channel', $channel)
            ->setParameter('locale', $locale)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $count > 0;
    }

    /**
     * @param string $slug
     * @param string $localeCode
     * @param string $channelCode
     *
     * @return PageInterface|null
     * @throws NonUniqueResultException
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
