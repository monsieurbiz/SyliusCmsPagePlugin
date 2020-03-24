<?php
declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\Channel;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="mbiz_cms_page")
 */
class Page implements PageInterface
{
    use TimestampableTrait, ToggleableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": true})
     */
    protected $enabled = true;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="\Sylius\Component\Channel\Model\Channel")
     * @ORM\JoinTable(
     *     name="mbiz_cms_page_channels",
     *     joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="channel_id", referencedColumnName="id")}
     * )
     */
    private $channels;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(name="created_at", type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->channels = new ArrayCollection();
    }

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
     * @return Collection
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * @param Channel $channel
     */
    public function addChannel(Channel $channel): void
    {
        $this->channels->add($channel);
    }

    /**
     * @param Channel $channel
     */
    public function removeChannel(Channel $channel): void
    {
        $this->channels->removeElement($channel);
    }


    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->getTranslation()->getTitle();
    }

    /**
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->getTranslation()->setTitle($title);
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
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->getTranslation()->setContent($content);
    }

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->getTranslation()->getMetaTitle();
    }

    /**
     * @param string|null $metaTitle
     * @return void
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->getTranslation()->setMetaTitle($metaTitle);
    }

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    /**
     * @param string|null $metaDescription
     * @return void
     */
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->getTranslation()->setMetaDescription($metaDescription);
    }

    /**
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getTranslation()->getMetaKeywords();
    }

    /**
     * @param string|null $metaKeywords
     * @return void
     */
    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->getTranslation()->setMetaKeywords($metaKeywords);
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->getTranslation()->setSlug($slug);
    }

    /**
     * @inheritDoc
     */
    protected function createTranslation(): PageTranslation
    {
        return new PageTranslation();
    }

    /**
     * @param string|null $locale
     *
     * @return PageTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        /** @var PageTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }
}
