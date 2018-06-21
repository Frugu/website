<?php

declare(strict_types=1);

namespace App\Entity\Auth;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="t_user_character")
 * @ORM\Entity(repositoryClass="App\Repository\Auth\UserCharacterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserCharacter
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Auth\User", inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $characterId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $characterName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $main;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * UserCharacter constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() === null) {
            $this->setUpdatedAt(new \DateTime());
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
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserCharacter
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getCharacterId(): ?int
    {
        return $this->characterId;
    }

    /**
     * @param int $characterId
     *
     * @return UserCharacter
     */
    public function setCharacterId(int $characterId): self
    {
        $this->characterId = $characterId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    /**
     * @param string $characterName
     *
     * @return UserCharacter
     */
    public function setCharacterName(string $characterName): self
    {
        $this->characterName = $characterName;

        return $this;
    }

    /**
     * @return bool
     */
    public function getMain(): ?bool
    {
        return $this->main;
    }

    /**
     * @param bool $main
     *
     * @return UserCharacter
     */
    public function setMain(bool $main): self
    {
        $this->main = $main;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return null|\DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        $this->setUpdatedAt(new \DateTime());
    }
}
