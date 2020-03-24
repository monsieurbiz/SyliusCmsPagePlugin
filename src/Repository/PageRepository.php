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
     * @return PageInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneEnabledBySlugAndLocale(string $slug, string $localeCode): ?PageInterface
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.translations', 'translation')
            ->where('translation.locale = :localeCode')
            ->andWhere('translation.slug = :slug')
            ->andWhere('p.enabled = true')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
