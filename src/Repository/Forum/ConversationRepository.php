<?php

declare(strict_types=1);

namespace App\Repository\Forum;

use App\Entity\Forum\Category;
use App\Entity\Forum\Conversation;
use App\Repository\AbstractRepository;
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
     * @return Conversation[]
     */
    public function findCategoryConversations(Category $category)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.category > :category')
            ->andWhere('c.parent IS NULL')
            ->setParameter('category', $category->getId())
            ->orderBy('c.createdAt')
            ->getQuery();

        return $qb->execute();
    }
}
