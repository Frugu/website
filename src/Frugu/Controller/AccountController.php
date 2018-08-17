<?php

namespace Frugu\Controller;

use Frugu\Entity\Auth\User;
use Frugu\Factory\Forum\BreadcrumbFactory;
use Frugu\Form\UserType;
use Frugu\Repository\Auth\UserRepository;
use Frugu\Repository\Exception\UnsupportedClassException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    /**
     * @Route("/account", name="account")
     *
     * @param BreadcrumbFactory $breadcrumbFactory
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return Response
     *
     * @throws UnsupportedClassException
     */
    public function account(BreadcrumbFactory $breadcrumbFactory, Request $request, UserRepository $userRepository)
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $userRepository->save($user);

            $this->addFlash('success', 'Account updated.');
        }

        return $this->render('account/account.html.twig', [
            'breadcrumb' => $breadcrumbFactory->create([[
                'name' => 'Account',
                'url' => 'account',
            ]]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/characters", name="account_characters")
     *
     * @param BreadcrumbFactory $breadcrumbFactory
     *
     * @return Response
     */
    public function characters(BreadcrumbFactory $breadcrumbFactory)
    {
        /** @var User $user */
        $user = $this->getUser();
        $characters = $user->getCharacters();

        return $this->render('account/characters.html.twig', [
            'breadcrumb' => $breadcrumbFactory->create([
                [
                    'name' => 'Account',
                    'url' => 'account',
                ], [
                    'name' => 'Characters',
                    'url' => 'account_characters',
                ],
            ]),
            'characters' => $characters,
        ]);
    }
}
