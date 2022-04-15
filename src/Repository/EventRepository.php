<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use phpDocumentor\Reflection\PseudoTypes\LiteralString;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
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
    public function update(Event $entity, bool $flush = true): void
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
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function search($campusSite, $keywords, $beginningDate, $endingDate, $organizer, $user, $registered, $notRegistered, $pastEvents)
    {
        $qb = $this->createQueryBuilder('search');
        if ($campusSite != null) {
            $qb->andWhere('search.campusSite = :val');
            $qb->setParameter('val', $campusSite);
        }

        if ($keywords != null) {
            $tabKW = explode(" ", $keywords);
            foreach ($tabKW as $keyword) {
                $qb->andWhere('LOWER(search.name) LIKE :word');
                $qb->setParameter('word', '%' . strtolower($keyword) . '%');
            }
        }

        if ($beginningDate != null) {
            $qb->andWhere('search.startTime BETWEEN :beginningDate AND :endingDate')
                ->setParameter('beginningDate', $beginningDate)
                ->setParameter('endingDate', $endingDate);
        }

        if ($organizer != null) {
            $qb->andWhere('search.organizer = :user')
                ->setParameter('user', $user);
        }

        if ($registered != null) {
            $qb->innerJoin('App\Entity\User', 'u')
                ->andWhere(':userMe MEMBER OF search.Users')
                ->andWhere(' :userMe MEMBER OF u.isRegistered')
                ->setParameter('userMe', $user);
        }

//        OU
//        if ($registered != null) {
//            $qb->leftJoin('search.Users','u')
//                ->andWhere('u = :userMe')
//                ->setParameter('userMe', $user->getId());
//        }

        if ($notRegistered != null) {
            $qb->leftJoin('search.Users', 'u')
                ->andWhere('u <> :userMe')
                ->setParameter('userMe', $user->getId());
        }

        if ($pastEvents != null){
            $qb->andWhere('search.startTime < :now')
                ->setParameter('now', new \DateTime('now'));
        }

        $req = $qb->getQuery();
        $result = $req->getResult();
        return $result;
    }
    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
