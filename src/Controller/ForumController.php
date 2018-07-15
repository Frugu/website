<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Forum\Category;
use App\Template\ForumRowGenerator;
use App\Manager\Forum\BreadcrumbManager;
use App\Manager\Forum\CategoryManager;
use App\Manager\Forum\ConversationManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @param ForumRowGenerator $forumRowGenerator
     * @param CategoryManager $categoryManager
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function index(ForumRowGenerator $forumRowGenerator, CategoryManager $categoryManager)
    {
        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create(),
            'rows' => $forumRowGenerator->generate($categoryManager->repository()->findAllRootCategories())
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category")
     *
     * @param ForumRowGenerator $forumRowGenerator
     * @param Category $category
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function forum(ForumRowGenerator $forumRowGenerator, Category $category)
    {
        $categories = [$category];

        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create($categories),
            'rows' => $forumRowGenerator->generate($categories)
        ]);
    }
}
