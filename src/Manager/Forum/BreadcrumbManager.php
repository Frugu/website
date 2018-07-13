<?php

declare(strict_types=1);

namespace App\Manager\Forum;

use App\Entity\Forum\Category;

class BreadcrumbManager
{
    public static function create($elements = [], $hasHome = true)
    {
        $breadcrumb = [];

        if ($hasHome) {
            $breadcrumb[] = ['url' => 'home', 'name' => 'Home'];
        }

        foreach ($elements as $element) {
            if ($element instanceof Category) {
                $breadcrumb = array_merge($breadcrumb, CategoryManager::breadcrumb($element));
            }
        }

        return $breadcrumb;
    }
}
