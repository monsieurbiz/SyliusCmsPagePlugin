<?php

namespace MonsieurBiz\SyliusCmsPagePlugin\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class ContentManagementUnit implements ContentManagementUnitInterface
{
    use TimestampableTrait;
    use ToggleableTrait;

    public function __construct()
    {
        $this->initializeChannelsCollection();
    }

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var Collection<int, ChannelInterface>
     */
    protected $channels;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @param ChannelInterface $channel
     * @return bool
     */
    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function initializeChannelsCollection(): void
    {
        $this->channels = new ArrayCollection();
    }
}
