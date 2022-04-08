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
    public function getId(): ?int;

    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getContent(): ?string;

    public function setContent(?string $content): void;

    public function getMetaTitle(): ?string;

    public function setMetaTitle(?string $metaTitle): void;

    public function getMetaDescription(): ?string;

    public function setMetaDescription(?string $metaDescription): void;

    public function getMetaKeywords(): ?string;

    public function setMetaKeywords(?string $metaKeywords): void;
}
