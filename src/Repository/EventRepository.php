<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\FighterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Event>
 *
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

    public function getVisibleEvents(FighterInterface $target, UserInterface $viewer)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->join('e.viewers', 'u')
            ->where($qb->expr()->eq('e.user', $target->getId()))
            ->where($qb->expr()->orX($qb->expr()->eq('e.user', $target->getId()), $qb->expr()->eq('e.npc', $target->getId())))
            ->andWhere($qb->expr()->eq('u.id', $viewer->getId()))
            ->orderBy('e.id', 'desc')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult();
    }
}
