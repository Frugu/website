<?php

declare(strict_types=1);

namespace App\Manager\Forum;

use App\Entity\Auth\User;
use App\Entity\Forum\Category;
use App\Entity\Forum\Conversation;
use Cocur\Slugify\Slugify;

class ConversationManager
{
    /**
     * Create a Conversation based on given name & description.
     *
     * @param string $name
     * @param string $content
     * @param User $author
     * @param Category $category
     * @param string $type
     *
     * @return Conversation
     *
     * @throws \Exception
     */
    public static function create(string $name, string $content, User $author, Category $category, string $type = 'normal'): Conversation
    {
        $conversation = new Conversation();
        $conversation->setName($name);
        $conversation->setSlug((new Slugify())->slugify($name));
        $conversation->setContent($content);
        $conversation->setAuthor($author);
        $conversation->setCategory($category);
        $conversation->setType($type);

        return $conversation;
    }
}
