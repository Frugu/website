<?php

declare(strict_types=1);

namespace Frugu\Repository;

use Frugu\Repository\Exception\UnsupportedClassException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Util\ClassUtils;

abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @param object $object
     *
     * @throws UnsupportedClassException
     */
    public function save(object $object): void
    {
        $objectClass = ClassUtils::getClass($object);
        if ($objectClass !== $this->getClassName()) {
            throw new UnsupportedClassException(sprintf('Provided object doesn\'t comply to current Repository entity class: "%s"', $objectClass));
        }

        $this->_em->persist($object);
        $this->_em->flush();
    }
}
