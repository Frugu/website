<?php

namespace App\DataFixtures;

use App\Manager\Forum\ConversationManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ConversationFixtures extends AbstractConversationFixtures implements DependentFixtureInterface
{
    public const CONVERSATION_COUNT = 1000;
    public const CONVERSATION_PREFIX = 'conversation-';

    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $now = new \DateTimeImmutable();
        for ($i = 0; $i < self::CONVERSATION_COUNT; ++$i) {
            $conversation = ConversationManager::create(
                $faker->sentence,
                $faker->text,
                $this->oneUser(),
                $this->oneCategory()
            );

            $date = clone $now;
            $date = $date->sub(new \DateInterval('P' .rand(1, 365). 'D'));

            $conversation->setCreatedAt($date);
            $conversation->setUpdatedAt($date);

            $manager->persist($conversation);
            $this->addReference(self::CONVERSATION_PREFIX . $i, $conversation);
        }

        $manager->flush();
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
