<?php

declare(strict_types=1);

namespace App\Entity\Auth;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="t_user")
 * @ORM\Entity(repositoryClass="App\Repository\Auth\UserRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\OneToMany(targetEntity="App\Entity\Auth\UserCharacter", mappedBy="user", orphanRemoval=true)
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
     */
    protected $username = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug = '';

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isAdmin = false;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $usernameUpdatedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    protected $updatedAt;

    /**
     * User constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->characters = new ArrayCollection();

        $this->setCreatedAt(new \DateTimeImmutable());
        if (null === $this->getUpdatedAt()) {
            $this->setUpdatedAt(new \DateTimeImmutable());
            $this->setUsernameUpdatedAt(new \DateTimeImmutable());
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
        $roles = array('ROLE_USER', 'ROLE_OAUTH_USER');

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
    public function eraseCredentials()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(UserInterface $user)
    {
        return $user->getUsername() === $this->getUsername();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @Assert\IsTrue(message="You can update the username only once every month.")
     *
     * @throws \Exception
     */
    public function isUsernameUpdatable()
    {
        $now = new \DateTimeImmutable();
        $oneMonthAfterLastUsernameUpdate = $this->usernameUpdatedAt->add(new \DateInterval('P1M'));

        return $oneMonthAfterLastUsernameUpdate < $now;
    }
}
