<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @extends ServiceEntityRepository<Position>
 *
 * @method Position|null find($id, $lockMode = null, $lockVersion = null)
 * @method Position|null findOneBy(array $criteria, array $orderBy = null)
 * @method Position[]    findAll()
 * @method Position[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Position::class);
    }

    public function getMapArround(Position $position, $offset = 5): array
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where($qb->expr()->lte('p.x', $position->getX() + $offset))
            ->andWhere($qb->expr()->gte('p.x', $position->getX() - $offset))
            ->andWhere($qb->expr()->lte('p.y', $position->getY() + $offset))
            ->andWhere($qb->expr()->gte('p.y', $position->getY() - $offset))
            ->orderBy('p.y', 'desc')
            ->addOrderBy('p.x', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function getFreePositionArround(Position $position, $offset = 5)
    {
        $qb = $this->createQueryBuilder('p');

        $positions = $qb
            ->leftJoin('p.ground', 'g')
            ->where($qb->expr()->lte('p.x', $position->getX() + $offset))
            ->andWhere($qb->expr()->gte('p.x', $position->getX() - $offset))
            ->andWhere($qb->expr()->lte('p.y', $position->getY() + $offset))
            ->andWhere($qb->expr()->gte('p.y', $position->getY() - $offset))
            ->andWhere($qb->expr()->eq('g.walkable', ':walkable'))
            ->setParameter('walkable', true)
            ->getQuery()
            ->getResult();
        shuffle($positions);

        return array_shift($positions);
    }

    public function getResurrectPosition($x = 0, $y = 0): Position
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
            ->where($qb->expr()->eq('p.x', $x))
            ->andWhere($qb->expr()->eq('p.y', $y))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
