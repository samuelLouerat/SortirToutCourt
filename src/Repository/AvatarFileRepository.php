<?php

namespace App\Repository;

use App\Entity\AvatarFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvatarFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvatarFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvatarFile[]    findAll()
 * @method AvatarFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvatarFile::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AvatarFile $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AvatarFile $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

}
