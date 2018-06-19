<?php

namespace App\Entity\Auth;

use App\Repository\Auth\UserCharacterRepository;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
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
     * @var UserCharacterRepository
     */
    protected $userCharacterRepository;

    /**
     * UserProvider constructor.
     * @param EntityManagerInterface $entityManager
     * @param SlugifyInterface $slugify
     * @param UserCharacterRepository $userCharacterRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SlugifyInterface $slugify, UserCharacterRepository $userCharacterRepository)
    {
        $this->entityManager = $entityManager;
        $this->slugify = $slugify;
        $this->userCharacterRepository = $userCharacterRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($data)
    {
        $data = json_decode($data, true);

        if($data === null) {
            throw new UsernameNotFoundException(sprintf('User not found "%s"', $data));
        }

        $user = null;
        $userCharacter = $this->userCharacterRepository->findByCharacterId($data['CharacterID']);

        if($userCharacter === null) {
            $userCharacter = new UserCharacter();
            $userCharacter->setCharacterId($data['CharacterID']);
            $userCharacter->setCharacterName($data['CharacterName']);
            $userCharacter->setMain(false);

            $user = new User($this->slugify);
            $user->setUsername($data['CharacterName']);
            $userCharacter->setUser($user);

            $this->entityManager->persist($user);
            $this->entityManager->persist($userCharacter);
            $this->entityManager->flush();
        } else {
            $user = $userCharacter->getUser();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return $this->loadUserByUsername(json_encode($response->getData()));
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
