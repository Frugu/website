<?php

declare(strict_types=1);

namespace Frugu\Repository\Forum;

use Frugu\Entity\Forum\Category;
use Frugu\Entity\Forum\Conversation;
use Frugu\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method void              save(Conversation $object)
 */
class ConversationRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * @param Category $category
     *
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countCategoryConversations(Category $category): int
    {
        $count = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.category = :category')
            ->andWhere('c.parent IS NULL')
            ->setParameter('category', $category->getId()->toString())
            ->getQuery()
            ->getOneOrNullResult();

        return $count[1];
    }

    /**
     * @param Category $category
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function findCategoryConversations(Category $category, int $offset = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.category = :category')
            ->andWhere('c.parent IS NULL')
            ->setParameter('category', $category->getId()->toString())
            ->orderBy('c.createdAt');

        if (null !== $offset && null !== $limit) {
            $qb = $qb
                    ->setFirstResult($offset)
                    ->setMaxResults($limit);
        }

        return $qb->getQuery()->execute();
    }
}
