<?php

namespace App\Action;

use App\Entity\Ground;
use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;

class Run extends AbstractAction
{
    const int STRENGTH_NEED = 5;

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
        return
            $to->getGround()->getName() != Ground::GROUND_WATER
            && null === $to->getFighter()
            && 2 === $this->getDistance($from, $to)
            && $from->getFighter()->getStrength() >= self::STRENGTH_NEED;
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $from->getFighter()->setStrength($from->getFighter()->getStrength() - self::STRENGTH_NEED);

        $from->getFighter()->setPosition($to);
        $this->em->flush();
    }
}
