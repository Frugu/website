<?php

declare(strict_types=1);

namespace Frugu\Factory\Forum;

use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\Conversation;

class BreadcrumbFactory
{
    /**
     * @var bool
     */
    private $hasHome = true;

    /**
     * @param bool $enable
     *
     * @return BreadcrumbFactory
     */
    public function hasHome(bool $enable): self
    {
        $this->hasHome = $enable;

        return $this;
    }

    public function create($elements = [], $hasHome = true)
    {
        $breadcrumb = [];

        if ($hasHome) {
            $breadcrumb[] = ['url' => 'home', 'name' => 'Home'];
        }

        foreach ($elements as $element) {
            if (is_array($element)) {
                $breadcrumb[] = $element;
            } elseif ($element instanceof Category) {
                $breadcrumb = array_merge($breadcrumb, $this->category($element));
            } elseif ($element instanceof Conversation) {
                $breadcrumb = array_merge($breadcrumb, $this->conversation($element));
            }
        }

        return $breadcrumb;
    }

    /**
     * @param Category $category
     *
     * @return array
     */
    private function category(Category $category)
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

    /**
     * @param Conversation $conversation
     *
     * @return array
     */
    private function conversation(Conversation $conversation)
    {
        $breadcrumb = $this->category($conversation->getCategory());
        $breadcrumb[] = ['id' => $conversation->getId(), 'name' => $conversation->getName(), 'route' => 'conversation'];

        return $breadcrumb;
    }
}
