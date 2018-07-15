<?php

declare(strict_types=1);

namespace App\Manager\Auth;

use App\Entity\Auth\User;
use App\Entity\Auth\UserCharacter;
use App\Manager\AbstractManager;
use App\Repository\Auth\UserRepository;
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
