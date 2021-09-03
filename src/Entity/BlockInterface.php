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

namespace MonsieurBiz\SyliusCmsPagePlugin\Entity;

use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Interface BlockInterface.
 */
interface BlockInterface extends TranslatableInterface
{
    public function __construct();

    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void;

    /**
     * {}
     */
    public function createTranslation(): BlockTranslation;

    /**
     * @param string|null $locale
     *
     * @return BlockTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface;
}
