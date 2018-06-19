<?php

namespace App\Entity\Auth;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Auth\UserCharacterRepository")
 */
class UserCharacter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

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
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return UserCharacter
     */
    public function setMain(bool $main): self
    {
        $this->main = $main;

        return $this;
    }
}
