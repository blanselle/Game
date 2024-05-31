<?php

namespace App\Action;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;

class Punch implements ActionInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getIdentifier(): string
    {
        return 'punch';
    }

    public function getName(): string
    {
        return 'Coup de poing';
    }

    public function support(Position $from, Position $to): bool
    {
        $distance = (int)(sqrt(pow($to->getX()-$from->getX(), 2) + pow($to->getY()-$from->getY(), 2)));

        return null !== $to->getUser() && 1 === $distance;
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $to->getUser()->setHealth($to->getUser()->getHealth() - 15);
        $this->em->flush();
    }
}
