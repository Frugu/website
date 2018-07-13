<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Forum\Category;
use App\Manager\Forum\BreadcrumbManager;
use App\Repository\Forum\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create(),
            'categories' => $categoryRepository->findAllRootCategories(),
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category")
     *
     * @param Category $category
     *
     * @return Response
     */
    public function forum(Category $category)
    {
        $categories = [$category];
        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create($categories),
            'categories' => $categories,
        ]);
    }
}
