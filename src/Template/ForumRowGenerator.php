<?php

declare(strict_types=1);

namespace App\Template;

use App\Entity\Forum\Category;
use App\Entity\Forum\CategoryType;
use App\Entity\Forum\Conversation;
use App\Manager\Forum\ConversationManager;
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
     * @param int $page
     * @param int $limit
     *
     * @return Paginator
     *
     * @throws \Exception
     */
    public function generate(array $categories, int $page = 1, int $limit = 20): Paginator
    {
        $rows = [];
        $count = 0;
        $ignoredCount = 0;

        $hasNoLimit = $limit === 0;
        $offset = ($page - 1) * $limit;
        $offsetEnd = $offset + $limit;

        /** @var Category $category */
        foreach ($categories as $category) {
            $rows[] = $this->fill($category, true);
            ++$ignoredCount;

            $hasHeader = false;
            $childs = $category->getChildren();
            $countChilds = count($childs);

            if ($hasNoLimit || Paginator::groupCheck($offset, $offsetEnd, $count, $countChilds)) {
                foreach ($childs as $child) {
                    if ($hasNoLimit || Paginator::exactCheck($offset, $offsetEnd, $count)) {
                        $rows[] = $this->fill($child);
                        $hasHeader = true;
                    }
                    ++$count;
                }
            } else {
                $count += $countChilds;
            }

            $countConversations = $this->conversationManager->repository()->countCategoryConversations($category);

            if ($hasHeader && $countConversations > 0) {
                $rows[] = $this->fillWithSeparator('Conversations');
                ++$ignoredCount;
            }

            if ($hasNoLimit || Paginator::groupCheck($offset, $offsetEnd, $count, $countConversations)) {
                $offsetRemainigStart = $offset + (count($rows) - $ignoredCount);

                $queryOffset = $offsetRemainigStart;
                $queryLimit = $offsetEnd - $offsetRemainigStart - 1;
                if ($hasNoLimit) {
                    $queryOffset = null;
                    $queryLimit = null;
                }

                $conversations = $this->conversationManager->repository()->findCategoryConversations(
                    $category,
                    $queryOffset,
                    $queryLimit
                );
                foreach ($conversations as $conversation) {
                    if ($hasNoLimit || Paginator::exactCheck($offset, $offsetEnd, $count)) {
                        $rows[] = $this->fill($conversation);
                    }
                    ++$count;
                }
            } else {
                $count += $countConversations;
            }
        }

        return new Paginator($rows, $count, $offset, $limit, function (int $page) use ($categories) {
            /** @var Category $category */
            $category = current($categories);
            return $this->urlGenerator->generate('category', ['slug' => $category->getSlug(), 'page' => $page]);
        });
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