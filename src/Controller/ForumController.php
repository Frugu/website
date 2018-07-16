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
     * @var ForumRowGenerator
     */
    protected $forumRowGenerator;

    public function __construct(ForumRowGenerator $forumRowGenerator)
    {
        $this->forumRowGenerator = $forumRowGenerator;
    }

    /**
     * @Route("/", name="home")
     *
     * @param CategoryManager $categoryManager
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function index(CategoryManager $categoryManager)
    {
        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create(),
            'paginator' => $this->forumRowGenerator->generate($categoryManager->repository()->findAllRootCategories(), 1, 0)
        ]);
    }

    /**
     * @Route("/category/{slug}/{page}", name="category", requirements={"page"="\d+"})
     *
     * @param Category $category
     * @param int $page
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function forum(Category $category, int $page = 1)
    {
        $categories = [$category];

        return $this->render('forum/index.html.twig', [
            'breadcrumb' => BreadcrumbManager::create($categories),
            'paginator' => $this->forumRowGenerator->generate($categories, $page)
        ]);
    }
}
