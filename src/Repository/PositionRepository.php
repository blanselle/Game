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

    public function getMapArround($x, $y, $offset): array
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where($qb->expr()->lte('p.x', $x + $offset))
            ->andWhere($qb->expr()->gte('p.x', $x - $offset))
            ->andWhere($qb->expr()->lte('p.y', $y + $offset))
            ->andWhere($qb->expr()->gte('p.y', $y - $offset))
            ->orderBy('p.y', 'desc')
            ->addOrderBy('p.x', 'asc')
            ->getQuery()
            ->getResult();
    }
}
