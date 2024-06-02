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

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/punch.html.twig', [
                'target' => $to->getFighter(),
                'puncher' => $from->getFighter(),
                'damages' => self::DAMAGES,
            ]),
            $from,
            -(self::DAMAGES)
        );

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/punch.html.twig', [
                'target' => $to->getFighter(),
                'puncher' => $from->getFighter(),
                'damages' => self::DAMAGES,
            ]),
            $to,
            -(self::DAMAGES)
        );

        $target = $to->getFighter();
        $this->report = $this->twig->render('game/report/punch.html.twig', [
            'damages' => self::DAMAGES,
            'publicName' => $target->getPublicName(),
            'KO' => 0 === $target->getHealth(),
        ]);
        $this->fighterManager->applyDamage($target,self::DAMAGES);
    }
}
