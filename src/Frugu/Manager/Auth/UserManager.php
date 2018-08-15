<?php

declare(strict_types=1);

namespace Frugu\Manager\Auth;

use Frugu\Entity\Auth\User;
use Frugu\Entity\Auth\UserCharacter;
use Frugu\Manager\AbstractManager;
use Frugu\Repository\Auth\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method UserRepository repository()
 */
class UserManager extends AbstractManager
{
    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    /**
     * Create an User based on UserCharacter details.
     *
     * @param UserCharacter $userCharacter
     *
     * @return User
     *
     * @throws \Exception
     */
    public static function createFromCharacter(UserCharacter &$userCharacter): User
    {
        $characterName = $userCharacter->getCharacterName();

        $user = new User();
        $user->setUsername($characterName);
        $user->setSlug((new Slugify())->slugify($characterName));
        $userCharacter->setUser($user);

        return $user;
    }
}
