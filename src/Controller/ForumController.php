<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Forum\Category;
use App\Template\ForumRowGenerator;
use App\Manager\Forum\BreadcrumbManager;
use App\Manager\Forum\CategoryManager;
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
            'paginator' => $forumRowGenerator->generate($categoryManager->repository()->findAllRootCategories(), 1, 0)
        ]);
    }

    /**
     * @Route("/category/{slug}/{page}", name="category", requirements={"page"="\d+"})
     *
     * @param ForumRowGenerator $forumRowGenerator
     * @param Category $category
     * @param int $page
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function forum(ForumRowGenerator $forumRowGenerator, Category $category, int $page = 1)
    {
        $categories = [$category];

        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create($categories),
            'paginator' => $forumRowGenerator->generate($categories, $page)
        ]);
    }
}
