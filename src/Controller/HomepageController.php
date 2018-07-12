<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Forum\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
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
        return $this->render('homepage/index.html.twig', [
            'categories' => $categoryRepository->findAllRootCategories()
        ]);
    }
}
