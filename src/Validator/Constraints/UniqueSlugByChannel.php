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

use Symfony\Component\Validator\Constraint;

final class UniqueSlugByChannel extends Constraint
{
    public string $message = 'monsieurbiz_cms_page.ui.slug.unique';

    public function validatedBy(): string
    {
        return self::class . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
