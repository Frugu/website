<?php

namespace App\DataFixtures;

use App\Manager\Forum\CategoryManager;
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

        for ($i = 0; $i < self::ROOT_CATEGORIES_COUNT; ++$i) {
            $category = CategoryManager::create($faker->sentence(3), $faker->text(64));
            $category->setType('group');

            $categories[] = $category;
            $manager->persist($category);
            $this->addReference(self::ROOT_CATEGORIES_PREFIX . $i, $category);
        }

        for ($i = 0; $i < self::NON_ROOT_CATEGORIES_COUNT; ++$i) {
            $category = CategoryManager::create($faker->sentence(3), $faker->text(64));

            $random = rand(0, self::ROOT_CATEGORIES_COUNT * 2);
            if ($random < self::ROOT_CATEGORIES_COUNT) {
                $parent = $categories[$random];
            } else {
                $parent = $categories[rand(self::ROOT_CATEGORIES_COUNT, count($categories) - 1)];
            }
            $category->setParent($parent);

            $manager->persist($category);
            $categories[] = $category;
            $this->addReference(self::NON_ROOT_CATEGORIES_PREFIX . $i, $category);
        }

        $manager->flush();

    }
}
