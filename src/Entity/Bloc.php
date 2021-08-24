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

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Webmozart\Assert\Assert;

class Bloc implements BlocInterface
{
    use TimestampableTrait;
    use ToggleableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var Collection<int, ChannelInterface>
     */
    protected $channels;

    /**
     * @var DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $title;

    /**
     * Bloc constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeChannelsCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ChannelInterface>
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * @param ChannelInterface $channel
     */
    public function addChannel(ChannelInterface $channel): void
    {
        $this->channels->add($channel);
    }

    /**
     * @param ChannelInterface $channel
     */
    public function removeChannel(ChannelInterface $channel): void
    {
        $this->channels->removeElement($channel);
    }

    public function initializeChannelsCollection(): void
    {
        $this->channels = new ArrayCollection();
    }

    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getTranslation()->getContent();
    }

    /**
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->getTranslation()->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): BlocTranslation
    {
        return new BlocTranslation();
    }

    /**
     * @param string|null $locale
     *
     * @return BlocTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        $translation = $this->doGetTranslation($locale);
        Assert::isInstanceOf($translation, BlocTranslationInterface::class);

        return $translation;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
