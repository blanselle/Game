<?php

namespace App\Repository;

use App\Entity\Position;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUsersArround(Position $position, $offset = 5): array
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->leftJoin('u.position', 'p')
            ->where($qb->expr()->lte('p.x', $position->getX() + $offset))
            ->andWhere($qb->expr()->gte('p.x', $position->getX() - $offset))
            ->andWhere($qb->expr()->lte('p.y', $position->getY() + $offset))
            ->andWhere($qb->expr()->gte('p.y', $position->getY() - $offset))
            ->getQuery()
            ->getResult();
    }
}
