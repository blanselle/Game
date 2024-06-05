<?php

namespace App\Repository;

use App\Entity\Equipment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipment>
 *
 * @method Equipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipment[]    findAll()
 * @method Equipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipment::class);
    }

    public function getOrderedWornEquipmentQueryBuilder(User $user, array $types, bool $worn): QueryBuilder
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->where($qb->expr()->eq('e.user', ':user'))
            ->andWhere($qb->expr()->in('e.type', ':types'))
            ->andWhere($qb->expr()->eq('e.worn', ':worn'))
            ->setParameters([
                ':user' => $user,
                'types' => $types,
                'worn' => $worn
            ]);
    }

    public function getOrderedWornEquipment(User $user, array $types, bool $worn)
    {
        return
            $this->getOrderedWornEquipmentQueryBuilder($user, $types, $worn)
            ->getQuery()
            ->getResult();
    }
}