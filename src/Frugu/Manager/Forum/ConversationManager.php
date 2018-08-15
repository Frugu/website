<?php

declare(strict_types=1);

namespace Frugu\Manager\Forum;

use Frugu\Entity\Auth\User;
use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\Conversation;
use Frugu\Manager\AbstractManager;
use Frugu\Repository\Forum\ConversationRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method ConversationRepository repository()
 */
class ConversationManager extends AbstractManager
{
    /**
     * CategoryManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Conversation::class);
    }

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

    /**
     * @param Conversation $conversation
     *
     * @return array
     */
    public static function breadcrumb(Conversation $conversation)
    {
        $breadcrumb = CategoryManager::breadcrumb($conversation->getCategory());
        $breadcrumb[] = ['id' => $conversation->getId(), 'name' => $conversation->getName(), 'route' => 'conversation'];

        return $breadcrumb;
    }
}
