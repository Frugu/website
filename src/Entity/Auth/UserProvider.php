<?php

declare(strict_types=1);

namespace App\Entity\Auth;

use App\Factory\Auth\UserFactory;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
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
     *
     * @param EntityManagerInterface $entityManager
     * @param SlugifyInterface       $slugify
     */
    public function __construct(EntityManagerInterface $entityManager, SlugifyInterface $slugify)
    {
        $this->entityManager = $entityManager;
        $this->slugify = $slugify;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['username' => $username]);
        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User not found "%s"', $username));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userCharacterRepository = $this->entityManager->getRepository(UserCharacter::class);

        $data = $response->getData();
        if (null === $data || (!isset($data['CharacterID']) && !isset($data['CharacterName']))) {
            throw new UsernameNotFoundException(sprintf('User not found "%s"', json_encode($data)));
        }

        $user = null;
        $userCharacter = $userCharacterRepository->findOneBy(['characterId' => $data['CharacterID']]);

        if (null === $userCharacter) {
            $userCharacter = new UserCharacter();
            $userCharacter->setCharacterId($data['CharacterID']);
            $userCharacter->setCharacterName($data['CharacterName']);
            $userCharacter->setMain(false);

            $user = UserFactory::createFromCharacter($userCharacter);

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
        return User::class === ClassUtils::getRealClass($class);
    }
}
