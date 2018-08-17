<?php

declare(strict_types=1);

namespace Frugu\Controller;

use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\Conversation;
use Frugu\Factory\Forum\BreadcrumbFactory;
use Frugu\Repository\Forum\CategoryRepository;
use Frugu\Template\ForumRowGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    /**
     * @var ForumRowGenerator
     */
    protected $forumRowGenerator;

    /**
     * ForumController constructor.
     *
     * @param ForumRowGenerator $forumRowGenerator
     */
    public function __construct(ForumRowGenerator $forumRowGenerator)
    {
        $this->forumRowGenerator = $forumRowGenerator;
    }

    /**
     * @Route("/", name="home")
     *
     * @param CategoryRepository $categoryRepository
     * @param BreadcrumbFactory $breadcrumbFactory
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function index(CategoryRepository $categoryRepository, BreadcrumbFactory $breadcrumbFactory)
    {
        return $this->render('forum/index.html.twig', [
            'breadcrumb' => $breadcrumbFactory->create(),
            'rows' => $this->forumRowGenerator->generate($categoryRepository->findAllRootCategories()),
        ]);
    }

    /**
     * @Route("/category/{slug}/{page}", name="category", requirements={"page"="\d+"})
     *
     * @param BreadcrumbFactory $breadcrumbFactory
     * @param Category $category
     * @param int      $page
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function forum(BreadcrumbFactory $breadcrumbFactory, Category $category, int $page = 1)
    {
        $categories = [$category];

        return $this->render('forum/index.html.twig', [
            'breadcrumb' => $breadcrumbFactory->create($categories),
            'rows' => $this->forumRowGenerator->generate($categories),
        ]);
    }

    /**
     * @Route("/conversation/{id}", name="conversation")
     *
     * @param BreadcrumbFactory $breadcrumbFactory
     * @param Conversation $conversation
     *
     * @return Response
     */
    public function conversation(BreadcrumbFactory $breadcrumbFactory, Conversation $conversation)
    {
        return $this->render('forum/conversation/conversation.html.twig', [
            'breadcrumb' => $breadcrumbFactory->create([$conversation]),
            'conversation' => $conversation,
        ]);
    }
}
