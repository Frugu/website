<?php

namespace Frugu\DataFixtures;

use Frugu\Entity\Auth\UserCharacter;
use Frugu\Manager\Auth\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USERS_COUNT = 2;
    public const USERS_PREFIX = 'user-';

    public function load(ObjectManager $manager)
    {
        $characters = [
            91901482 => 'Mealtime',
            93577264 => 'Sandy Stackhouse',
        ];

        $admins = [
            91901482,
        ];

        $current = 0;
        foreach ($characters as $characterId => $characterName) {
            $userCharacter = new UserCharacter();
            $userCharacter->setCharacterId($characterId);
            $userCharacter->setCharacterName($characterName);
            $userCharacter->setMain(true);
            $user = UserManager::createFromCharacter($userCharacter);

            if (in_array($characterId, $admins)) {
                $user->setIsAdmin(true);
            }

            $this->addReference(self::USERS_PREFIX . $current, $user);
            $manager->persist($user);
            $manager->persist($userCharacter);

            ++$current;
        }
        $manager->flush();
    }
}
