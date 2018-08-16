<?php

declare(strict_types=1);

namespace Frugu\Entity\Auth;

use Frugu\Entity\Forum\Conversation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="t_user")
 * @ORM\Entity(repositoryClass="Frugu\Repository\Auth\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     *
     * @var UuidInterface
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Frugu\Entity\Auth\UserCharacter", mappedBy="user", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    protected $characters;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 6,
     *     max = 64,
     *     minMessage = "Your username  must be at least {{ limit }} characters long.",
     *     maxMessage = "Your username cannot be longer than {{ limit }} characters."
     * )
     *
     * @var string
     */
    protected $username = '';

    /**
     * @Gedmo\Slug(fields={"username"})
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $isAdmin = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="change", field={"username"})
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $usernameUpdatedAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Frugu\Entity\Forum\Conversation", mappedBy="author")
     *
     * @var ArrayCollection
     */
    private $conversations;

    /**
     * User constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|UserCharacter[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Used for EasyAdmin references.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'User "'.$this->getUsername().'"';
    }

    /**
     * @param string $username
     *
     * @return User
     *
     * @throws \Exception
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        $this->setUsernameUpdatedAt(new \DateTimeImmutable());

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
     * @return User
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     *
     * @return User
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = ['ROLE_USER', 'ROLE_OAUTH_USER'];

        if ($this->isAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
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
     * @return User
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return null|\DateTimeInterface
     */
    public function getUsernameUpdatedAt(): ?\DateTimeInterface
    {
        return $this->usernameUpdatedAt;
    }

    /**
     * @param \DateTimeInterface $usernameUpdatedAt
     *
     * @return User
     */
    public function setUsernameUpdatedAt(\DateTimeInterface $usernameUpdatedAt): self
    {
        $this->usernameUpdatedAt = $usernameUpdatedAt;

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
     * @return User
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(UserInterface $user): bool
    {
        return $user->getUsername() === $this->getUsername();
    }

    /**
     * @Assert\IsTrue(message="You can update the username only once every month.")
     *
     * @throws \Exception
     */
    public function isUsernameUpdatable(): bool
    {
        $now = new \DateTimeImmutable();
        $oneMonthAfterLastUsernameUpdate = $this->usernameUpdatedAt->add(new \DateInterval('P1M'));

        return $oneMonthAfterLastUsernameUpdate < $now;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    /**
     * @param Conversation $conversation
     *
     * @return User
     */
    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setAuthor($this);
        }

        return $this;
    }
}
