<?php

namespace Frugu\DataFixtures;

use Frugu\Entity\Forum\Conversation;
use Frugu\Manager\Forum\ConversationManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ConversationReplyFixtures extends AbstractConversationFixtures implements DependentFixtureInterface
{
    public const REPLY_COUNT = 10000;

    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::REPLY_COUNT; ++$i) {
            /** @var Conversation $parent */
            $parent = $this->getReference(ConversationFixtures::CONVERSATION_PREFIX . rand(0, ConversationFixtures::CONVERSATION_COUNT - 1));

            $conversation = ConversationManager::create(
                $parent->getName(),
                $faker->text,
                $this->oneUser(),
                $parent->getCategory()
            );
            $conversation->setType('reply');
            $conversation->setParent($parent);

            /** @var \DateTime $createdAt */
            $createdAt = clone $parent->getCreatedAt();
            $createdAt = $createdAt->add(new \DateInterval('PT' .rand(1, 720). 'H'));
            $conversation->setCreatedAt($createdAt);
            $conversation->setUpdatedAt($createdAt);

            $manager->persist($conversation);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            ConversationFixtures::class,
        ];
    }
}
