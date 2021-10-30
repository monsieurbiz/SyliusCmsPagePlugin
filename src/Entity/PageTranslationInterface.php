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

namespace MonsieurBiz\SyliusCmsPagePlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Interface PageTranslationInterface.
 */
interface PageTranslationInterface extends ResourceInterface, TranslationInterface, SlugAwareInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void;

    /**
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void;

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * @param string|null $metaTitle
     *
     * @return void
     */
    public function setMetaTitle(?string $metaTitle): void;

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * @param string|null $metaDescription
     *
     * @return void
     */
    public function setMetaDescription(?string $metaDescription): void;

    /**
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * @param string|null $metaKeywords
     *
     * @return void
     */
    public function setMetaKeywords(?string $metaKeywords): void;
}
