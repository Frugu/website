<?php

declare(strict_types=1);

namespace Frugu\Entity\Forum;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="t_category")
 * @ORM\Entity(repositoryClass="Frugu\Repository\Forum\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name = '';

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=128)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $description = '';

    /**
     * @ORM\ManyToOne(targetEntity="Frugu\Entity\Forum\Category", inversedBy="children")
     *
     * @var null|Category
     */
    private $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="Frugu\Entity\Forum\Category", mappedBy="parent")
     *
     * @var ArrayCollection
     */
    private $children;

    /**
     * Possible types: group, forum.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $type = CategoryType::FORUM;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $updatedAt;

    /**
     * Category constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    /**
     * Used for EasyAdmin references.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Category "'.$this->getName().'"';
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Category
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return null|Category
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * @param null|Category $parent
     *
     * @return Category
     */
    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Category
     */
    public function setType(string $type): self
    {
        if (!in_array($type, [CategoryType::GROUP, CategoryType::FORUM])) {
            throw new \InvalidArgumentException('Category type can only be: `'.CategoryType::GROUP.'` or `'.CategoryType::FORUM.'`');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return Category
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return null|\DateTimeInterface
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     *
     * @return Category
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
