<?php

namespace App\Entity\Auth;

use Cocur\Slugify\SlugifyInterface;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

class UserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SlugifyInterface
     */
    protected $slugify;

    /**
     * UserProvider constructor.
     * @param EntityManagerInterface $entityManager
     * @param SlugifyInterface $slugify
     */
    public function __construct(EntityManagerInterface $entityManager, SlugifyInterface $slugify)
    {
        $this->entityManager = $entityManager;
        $this->slugify = $slugify;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $data = $response->getData();

        $character = new UserCharacter();
        $character->setCharacterId($data['CharacterID']);
        $character->setCharacterName($data['CharacterName']);
        $character->setMain(false);

        $user = new User($this->slugify);
        $user->setUsername($data['CharacterName']);
        $character->setUser($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($character);
        $this->entityManager->flush();

        var_dump($user->getId()->toString());
        var_dump($character->getId()->toString());
        exit;
    }
}
