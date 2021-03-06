<?php

namespace Frugu\Repository\Forum;

use Frugu\Entity\Forum\Category;
use Frugu\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method void          save(Category $object)
 */
class CategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function findAllRootCategories(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.parent IS NULL')
            ->getQuery();

        return $qb->execute();
    }
}
