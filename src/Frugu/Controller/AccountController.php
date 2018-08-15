<?php

namespace Frugu\Controller;

use Frugu\Entity\Auth\User;
use Frugu\Form\UserType;
use Frugu\Manager\Auth\UserManager;
use Frugu\Manager\Forum\BreadcrumbManager;
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
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @throws UnsupportedClassException
     */
    public function account(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $userManager->repository()->save($user);

            $this->addFlash('success', 'Account updated.');
        }

        return $this->render('account/account.html.twig', [
            'breadcrumb' => BreadcrumbManager::create([[
                'name' => 'Account',
                'url' => 'account',
            ]]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/characters", name="account_characters")
     */
    public function characters()
    {
        /** @var User $user */
        $user = $this->getUser();
        $characters = $user->getCharacters();

        return $this->render('account/characters.html.twig', [
            'breadcrumb' => BreadcrumbManager::create([
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
