<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        $user = $this->getUser();
        $formBuilder = $this->createFormBuilder($user);
        $formBuilder->add(
            'username',
            TextType::class,
            [
                'help' => 'My Help Message'
            ]
        );
        $formBuilder->add('update', SubmitType::class);

        return $this->render('account/index.html.twig', [
            'form' => $formBuilder->getForm()->createView()
        ]);
    }
}
