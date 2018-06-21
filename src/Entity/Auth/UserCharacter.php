<?php

declare(strict_types=1);

namespace App\Entity\Auth;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="t_user_character")
 * @ORM\Entity(repositoryClass="App\Repository\Auth\UserCharacterRepository")
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
}
