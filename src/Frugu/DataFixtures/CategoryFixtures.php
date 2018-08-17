<?php

namespace Frugu\DataFixtures;

use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\CategoryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public const MAX_LEVEL = 3;

    public const ROOT_CATEGORIES_COUNT = 3;

    public const ROOT_CATEGORIES_PREFIX = 'root-categories-';

    public const NON_ROOT_CATEGORIES_COUNT = 15;

    public const NON_ROOT_CATEGORIES_PREFIX = 'non-root-categories-';

    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $categories = [];
        $level = [];

        for ($i = 0; $i < self::ROOT_CATEGORIES_COUNT; ++$i) {
            $category = new Category();
            $category->setName($faker->sentence(3));
            $category->setDescription($faker->text(64));
            $category->setType(CategoryType::GROUP);

            $categories[] = $category;
            $manager->persist($category);
            $this->addReference(self::ROOT_CATEGORIES_PREFIX.$i, $category);
            $level[$category->getId()->toString()] = 1;
        }

        for ($i = 0; $i < self::NON_ROOT_CATEGORIES_COUNT; ++$i) {
            $category = new Category();
            $category->setName($faker->sentence(3));
            $category->setDescription($faker->text(64));

            $index = null;
            do {
                $random = rand(0, self::ROOT_CATEGORIES_COUNT * 2);
                if ($random < self::ROOT_CATEGORIES_COUNT) {
                    $index = $random;
                } else {
                    $index = rand(self::ROOT_CATEGORIES_COUNT, count($categories) - 1);
                }
            } while (is_null($index) || !isset($categories[$index]) || $level[$categories[$index]->getId()->toString()] >= self::MAX_LEVEL);
            $category->setParent($categories[$index]);

            $manager->persist($category);
            $categories[] = $category;
            $this->addReference(self::NON_ROOT_CATEGORIES_PREFIX.$i, $category);
            $level[$category->getId()->toString()] = $level[$categories[$index]->getId()->toString()] + 1;
        }

        $manager->flush();
    }
}
