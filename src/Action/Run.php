<?php

namespace App\Action;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;

class Run implements ActionInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getIdentifier(): string
    {
        return 'run';
    }

    public function getName(): string
    {
        return 'Courir';
    }

    public function support(Position $from, Position $to): bool
    {
        $distance = (int)(sqrt(pow($to->getX()-$from->getX(), 2) + pow($to->getY()-$from->getY(), 2)));

        return
            $to->getGround()->getName() != 'water'
            && null === $to->getUser()
            && 2 === $distance;
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $from->getUser()->setPosition($to);
        $this->em->flush();
    }
}
