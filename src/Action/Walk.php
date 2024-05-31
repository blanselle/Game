<?php

namespace App\Action;

use App\Entity\Ground;
use App\Entity\Position;

class Walk extends AbstractAction
{
    public function getIdentifier(): string
    {
        return 'walk';
    }

    public function getName(): string
    {
        return 'Marcher';
    }

    public function support(Position $from, Position $to): bool
    {
        return
            $to->getGround()->getName() != Ground::GROUND_WATER
            && null === $to->getFighter()
            && 1 === $this->getDistance($from, $to);
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $from->getFighter()->setPosition($to);
        $this->em->flush();
    }
}
