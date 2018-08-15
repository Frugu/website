<?php

declare(strict_types=1);

namespace Frugu\Template;

use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\CategoryType;
use Frugu\Entity\Forum\Conversation;
use Frugu\Manager\Forum\ConversationManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ForumRowGenerator
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var ConversationManager
     */
    protected $conversationManager;

    /**
     * ForumGenerator constructor.
     *
     * @param Environment $twig
     * @param UrlGeneratorInterface $urlGenerator
     * @param ConversationManager $conversationManager
     */
    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator, ConversationManager $conversationManager)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->conversationManager = $conversationManager;
    }

    /**
     * @param array $categories
     *
     * @return array
     *
     * @throws \Exception
     */
    public function generate(array $categories): array
    {
        $rows = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $rows[] = $this->fill($category, true);

            $childs = $category->getChildren();
            foreach ($childs as $child) {
                $rows[] = $this->fill($child);
            }

            $countConversations = $this->conversationManager->repository()->countCategoryConversations($category);
            if ($countConversations > 0) {
                $rows[] = $this->fillWithSeparator('Conversations');
            }

            $conversations = $this->conversationManager->repository()->findCategoryConversations($category);
            foreach ($conversations as $conversation) {
                $rows[] = $this->fill($conversation);
            }
        }

        return $rows;
    }

    /**
     * @param $object
     * @param bool $forceGroup
     *
     * @return ForumRowTemplate
     *
     * @throws \Exception
     */
    protected function fill(object $object, $forceGroup = false): ForumRowTemplate
    {
        if ($object instanceof Category) {
            return $this->fillWithCategory($object, $forceGroup);
        }
        if ($object instanceof Conversation) {
            return $this->fillWithConversation($object);
        }
        throw new \Exception('Can\'t use given object to fill a ForumRow.');
    }

    /**
     * @param Category $category
     * @param bool $forceGroup
     *
     * @return ForumRowTemplate
     *
     * @throws \Exception
     */
    protected function fillWithCategory(Category $category, $forceGroup = false): ForumRowTemplate
    {
        $row = new ForumRowTemplate();
        $row->name = $category->getName();
        $row->type = ForumRowTemplateType::CATEGORY;
        $row->subtype = $forceGroup ? CategoryType::GROUP : $category->getType();
        $row->preview = $category->getDescription();
        $row->link = $this->urlGenerator->generate('category', ['slug' => $category->getSlug()]);
        $row->details = $this->makeCategoryDetails($category);
        $row->additionalLinks = $this->makeCategoryChildLinks($category);

        return $row;
    }

    /**
     * @param Category $category
     *
     * @return array
     */
    protected function makeCategoryDetails(Category $category): array
    {
        $conversations = $this->conversationManager->repository()->findCategoryConversations($category);

        $first = count($conversations). ' <i class="far fa-envelope"></i>';
        $second = '//';
        $third = '//';
        return [$first, $second, $third];
    }

    /**
     * @param Category $category
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function makeCategoryChildLinks(Category $category): array
    {
        $links = [];
        foreach ($category->getChildren() as $child) {
            $links[] = $this->twig->render('forum/category/childLink.html.twig', [
                'category' => $child
            ]);
        }
        return $links;
    }

    /**
     * @param string $name
     * @param string $description
     *
     * @return ForumRowTemplate
     *
     * @throws \Exception
     */
    protected function fillWithSeparator(string $name, string $description = ''): ForumRowTemplate
    {
        $row = new ForumRowTemplate();
        $row->name = $name;
        $row->type = ForumRowTemplateType::SEPARATOR;
        $row->preview = $description;

        return $row;
    }

    /**
     * @param Conversation $conversation
     *
     * @return ForumRowTemplate
     *
     * @throws \Exception
     */
    protected function fillWithConversation(Conversation $conversation): ForumRowTemplate
    {
        $row = new ForumRowTemplate();
        $row->name = $conversation->getName();
        $row->type = ForumRowTemplateType::CONVERSATION;
        $row->subtype = $conversation->getType();
        $row->preview = $conversation->getContent();
        $row->link = $this->urlGenerator->generate('conversation', ['id' => $conversation->getId()]);
        $row->details = null;
        $row->additionalLinks = null;

        return $row;
    }
}
