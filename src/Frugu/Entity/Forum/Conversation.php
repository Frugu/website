<?php

declare(strict_types=1);

namespace Frugu\Entity\Forum;

use Frugu\Entity\Auth\User;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="t_conversation")
 * @ORM\Entity(repositoryClass="Frugu\Repository\Forum\ConversationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Conversation
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
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $slug = '';

    /**
     * Possible types: normal, sticky, announcement, global, reply
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $type = ConversationType::NORMAL;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $content = '';

    /**
     * @ORM\ManyToOne(targetEntity="Frugu\Entity\Forum\Category")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Category
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Frugu\Entity\Auth\User", inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Frugu\Entity\Forum\Conversation")
     *
     * @var null|Conversation
     */
    private $parent;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var ?\DateTimeInterface
     */
    private $deletedAt = null;

    /**
     * Conversation constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        if (null === $this->getUpdatedAt()) {
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Conversation
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Conversation
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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
     * @return Conversation
     *
     * @throws \Exception
     */
    public function setType(string $type): self
    {
        if ($type === ConversationType::NORMAL ||
            $type === ConversationType::STICKY ||
            $type === ConversationType::ANNOUNCEMENT ||
            $type === ConversationType::GLOBAL ||
            $type === ConversationType::REPLY) {
            $this->type = $type;

            return $this;
        }

        throw new \Exception('Given $type doesn\'t comply with possibles types.');
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return Conversation
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return Conversation
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

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
     * @return Conversation
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     *
     * @return Conversation
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return null|\DateTimeInterface
     */
    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTimeInterface $deletedAt
     *
     * @return Conversation
     */
    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return null|Conversation
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * @param Conversation $parent
     *
     * @return Conversation
     */
    public function setParent(Conversation $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Conversation
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Used for EasyAdmin references.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Conversation "'.$this->getName().'"';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
