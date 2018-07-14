<?php

namespace App\DataFixtures;

use App\Entity\Auth\User;
use App\Entity\Forum\Category;
use App\Manager\Forum\ConversationManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    public const CONVERSATIONS_COUNT = 1000;

    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $now = new \DateTimeImmutable();
        for ($i = 0; $i < self::CONVERSATIONS_COUNT; ++$i) {
            $conversation = ConversationManager::create(
                $faker->sentence,
                $faker->text,
                $this->oneUser(),
                $this->oneCategory()
            );

            $interval = new \DateInterval('P' .rand(1, 365). 'D');
            $conversation->setCreatedAt($now->sub($interval));

            $manager->persist($conversation);
        }

        $manager->flush();
    }

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

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
