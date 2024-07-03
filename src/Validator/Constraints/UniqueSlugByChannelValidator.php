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

namespace MonsieurBiz\SyliusCmsPagePlugin\Validator\Constraints;

use MonsieurBiz\SyliusCmsPagePlugin\Entity\PageInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Repository\PageRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class UniqueSlugByChannelValidator extends ConstraintValidator
{
    private PageRepositoryInterface $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var PageInterface $value */
        Assert::isInstanceOf($value, PageInterface::class);
        /** @var UniqueSlugByChannel $constraint */
        Assert::isInstanceOf($constraint, UniqueSlugByChannel::class);

        // Check if the slug is unique for each channel and locale
        foreach ($value->getTranslations() as $translation) {
            foreach ($value->getChannels() as $channel) {
                if ($this->pageRepository->existsOneByChannelAndSlug(
                    $channel,
                    $translation->getLocale(),
                    $translation->getSlug(),
                    $value->getId() ? [$value] : []
                )) {
                    $this->context->buildViolation($constraint->message, [
                        '%channel%' => $channel->getCode(),
                        '%locale%' => $translation->getLocale(),
                    ])
                        ->atPath(sprintf('translations[%s].slug', $translation->getLocale()))
                        ->addViolation()
                    ;
                }
            }
        }
    }
}
