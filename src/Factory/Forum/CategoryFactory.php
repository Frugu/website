<?php

declare(strict_types=1);

namespace App\Factory\Forum;

use App\Entity\Forum\Category;
use Cocur\Slugify\Slugify;

class CategoryFactory
{
    /**
     * Create a Category based on given name & description.
     *
     * @param string $name
     * @param string $description
     *
     * @return Category
     *
     * @throws \Exception
     */
    public static function create(string $name, string $description): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug((new Slugify())->slugify($name));
        $category->setDescription($description);

        return $category;
    }
}
