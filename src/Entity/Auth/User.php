<?php

declare(strict_types=1);

namespace App\Entity\Auth;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="t_user")
 * @ORM\Entity(repositoryClass="App\Repository\Auth\UserRepository")
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
     * User constructor.
     */
    public function __construct()
    {
        $this->characters = new ArrayCollection();
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
     * Used for EasyAdmin references
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
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

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

        if($this->isAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
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
}
