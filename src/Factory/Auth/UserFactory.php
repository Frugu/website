<?php

declare(strict_types=1);

namespace App\Factory\Auth;

use App\Entity\Auth\User;
use App\Entity\Auth\UserCharacter;
use Cocur\Slugify\Slugify;

class UserFactory
{
    /**
     * Create an User based on UserCharacter details.
     *
     * @param UserCharacter $userCharacter
     *
     * @return User
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
