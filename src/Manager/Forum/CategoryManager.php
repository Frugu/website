<?php

declare(strict_types=1);

namespace App\Manager\Forum;

use App\Entity\Forum\Category;
use Cocur\Slugify\Slugify;

class CategoryManager
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

    /**
     * @param Category $category
     *
     * @return array
     */
    public static function breadcrumb(Category $category)
    {
        $breadcrumb = [];
        array_unshift($breadcrumb, ['slug' => $category->getSlug(), 'name' => $category->getName(), 'route' => 'category']);

        $parent = $category->getParent();
        while (null !== $parent) {
            array_unshift($breadcrumb, ['slug' => $parent->getSlug(), 'name' => $parent->getName(), 'route' => 'category']);
            $parent = $parent->getParent();
        }

        return $breadcrumb;
    }
}