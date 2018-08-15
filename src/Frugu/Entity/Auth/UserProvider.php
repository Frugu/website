<?php

declare(strict_types=1);

namespace Frugu\Entity\Auth;

use Frugu\Manager\Auth\UserManager;
use Frugu\Repository\Auth\UserCharacterRepository;
use Frugu\Repository\Auth\UserRepository;
use Doctrine\Common\Util\ClassUtils;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserCharacterRepository
     */
    protected $userCharacterRepository;

    /**
     * UserProvider constructor.
     *
     * @param UserRepository          $userRepository
     * @param UserCharacterRepository $userCharacterRepository
     */
    public function __construct(UserRepository $userRepository, UserCharacterRepository $userCharacterRepository)
    {
        $this->userRepository = $userRepository;
        $this->userCharacterRepository = $userCharacterRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
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
        $data = $response->getData();
        if (null === $data || (!isset($data['CharacterID']) && !isset($data['CharacterName']))) {
            throw new UsernameNotFoundException(sprintf('User not found "%s"', json_encode($data)));
        }

        $user = null;
        $userCharacter = $this->userCharacterRepository->findOneBy(['characterId' => $data['CharacterID']]);

        if (null === $userCharacter) {
            $userCharacter = new UserCharacter();
            $userCharacter->setCharacterId($data['CharacterID']);
            $userCharacter->setCharacterName($data['CharacterName']);
            $userCharacter->setMain(false);

            $user = UserManager::createFromCharacter($userCharacter);
            $this->userRepository->save($user);
            $this->userCharacterRepository->save($userCharacter);
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
