<?php

namespace App\Action;

use App\Entity\Position;

class Punch extends AbstractAction
{
    const int STRENGTH_NEED = 5;
    const int DAMAGES = 5;

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
        return
            null !== $to->getFighter()
            && 1 === $this->getDistance($from, $to)
            && $from->getFighter()->getStrength() >= self::STRENGTH_NEED;
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $this->fighterManager->decreaseStrength($from->getFighter(), self::STRENGTH_NEED);

        $target = $to->getFighter();
        $this->fighterManager->applyDamage($target,self::DAMAGES);

        $this->report = $this->twig->render('game/report/punch.html.twig', [
            'damages' => self::DAMAGES,
            'publicName' => $target->getPublicName(),
            'KO' => 0 === $target->getHealth(),
        ]);
    }
}
