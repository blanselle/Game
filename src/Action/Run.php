<?php

namespace App\Action;

use App\Entity\Ground;
use App\Entity\Position;

class Run extends AbstractAction
{
    const int STRENGTH_NEEDS = 5;

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
            $to->getGround()->isWalkable()
            && null === $to->getFighter()
            && 2 === $this->getDistance($from, $to)
            && $from->getFighter()->getStrength() >= self::STRENGTH_NEEDS;
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $this->fighterManager->move($from, $to, self::STRENGTH_NEEDS);
    }
}
