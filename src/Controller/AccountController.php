<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        $form = $this->createForm(UserType::class, $this->getUser());

        return $this->render('account/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
