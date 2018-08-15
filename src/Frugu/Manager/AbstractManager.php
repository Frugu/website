<?php

namespace Frugu\Manager;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ServiceEntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param EntityManagerInterface $entityManager
     * @param $class
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($class);
        $this->class = $class;
    }

    /**
     * @return ServiceEntityRepository
     */
    public function repository()
    {
        return $this->repository;
    }
}
