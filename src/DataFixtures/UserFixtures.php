<?php

namespace App\DataFixtures;

use App\Entity\Auth\UserCharacter;
use App\Manager\Auth\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $characters = [
            91901482 => 'Mealtime',
            93577264 => 'Sandy Stackhouse',
        ];

        $admins = [
            91901482,
        ];

        foreach ($characters as $characterId => $characterName) {
            $userCharacter = new UserCharacter();
            $userCharacter->setCharacterId($characterId);
            $userCharacter->setCharacterName($characterName);
            $userCharacter->setMain(true);
            $user = UserManager::createFromCharacter($userCharacter);

            if (in_array($characterId, $admins)) {
                $user->setIsAdmin(true);
            }

            $manager->persist($user);
            $manager->persist($userCharacter);
        }
        $manager->flush();
    }
}
