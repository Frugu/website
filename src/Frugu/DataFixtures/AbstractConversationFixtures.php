<?php

namespace Frugu\DataFixtures;

use Frugu\Entity\Auth\User;
use Frugu\Entity\Forum\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;

abstract class AbstractConversationFixtures extends Fixture
{
    /**
     * @return User
     */
    protected function oneUser(): User
    {
        $random = rand(0, UserFixtures::USERS_COUNT - 1);

        /** @var User $user */
        $user = $this->getReference(UserFixtures::USERS_PREFIX . $random);
        return $user;
    }

    protected function oneCategory(): Category
    {
        $random = rand(0, CategoryFixtures::NON_ROOT_CATEGORIES_COUNT - 1);

        /** @var Category $category */
        $category = $this->getReference(CategoryFixtures::NON_ROOT_CATEGORIES_PREFIX . $random);
        return $category;
    }
}