<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig_SimpleFunction;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoggedExtension extends AbstractExtension
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * LoggedExtension constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('getUser', array($this, 'getUser'))
        );
    }

    protected function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}