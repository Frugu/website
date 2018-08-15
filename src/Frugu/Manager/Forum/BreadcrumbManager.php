<?php

declare(strict_types=1);

namespace Frugu\Manager\Forum;

use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\Conversation;

class BreadcrumbManager
{
    public static function create($elements = [], $hasHome = true)
    {
        $breadcrumb = [];

        if ($hasHome) {
            $breadcrumb[] = ['url' => 'home', 'name' => 'Home'];
        }

        foreach ($elements as $element) {
            if (is_array($element)) {
                $breadcrumb[] = $element;
            } elseif ($element instanceof Category) {
                $breadcrumb = array_merge($breadcrumb, CategoryManager::breadcrumb($element));
            } elseif ($element instanceof Conversation) {
                $breadcrumb = array_merge($breadcrumb, ConversationManager::breadcrumb($element));
            }
        }

        return $breadcrumb;
    }
}
