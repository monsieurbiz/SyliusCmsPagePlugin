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

use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Timestampable;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * Interface PageInterface.
 */
interface PageInterface extends ResourceInterface, TranslatableInterface, ToggleableInterface, SlugAwareInterface, CodeAwareInterface, TimestampableInterface, Timestampable
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * @param string|null $title
     *
     * @return void
     */
    public function setCode(?string $title): void;

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

    /**
     * @return Collection<int, ChannelInterface>
     */
    public function getChannels(): Collection;

    /**
     * @param ChannelInterface $channel
     *
     * @return void
     */
    public function addChannel(ChannelInterface $channel): void;

    /**
     * @param ChannelInterface $channel
     *
     * @return void
     */
    public function removeChannel(ChannelInterface $channel): void;

    /**
     * @param ChannelInterface $channel
     *
     * @return bool
     */
    public function hasChannel(ChannelInterface $channel): bool;
}
