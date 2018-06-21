<?php

declare(strict_types=1);

namespace App\Repository\Auth;

use App\Entity\Auth\UserCharacter;
use App\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCharacter[]    findAll()
 * @method UserCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method void               save(UserCharacter $object)
 */
class UserCharacterRepository extends AbstractRepository
{
    /**
     * UserCharacterRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserCharacter::class);
    }
}
