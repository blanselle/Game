<?php

namespace App\Action;

use App\Entity\Position;

class Punch extends AbstractAction
{
    const int STRENGTH_NEED = 5;

    private int $damage;

    public function getIdentifier(): string
    {
        return 'punch';
    }

    public function getName(): string
    {
        return 'Coup de poing';
    }

    public function getDamage()
    {
        return $this->damage;
    }

    public function setDamage(int $damage)
    {
        $this->damage = $damage;
    }

    public function support(Position $from, Position $to): bool
    {
        return
            null !== $to->getFighter()
            && 1 === $this->getDistance($from, $to)
            && $from->getFighter()->getStrength() >= self::STRENGTH_NEED
            && $from->getFighter()->hasAtLeastOneFreeHand();
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $target = $to->getFighter();
        $attacker = $from->getFighter();

        $damage = floor($from->getFighter()->getStrength() / 10) - $to->getFighter()->getArmorLevel();

        if (0 > $damage) {
            $damage = 0;
        }

        $this->setDamage($damage);
        $this->fighterManager->applyDamage($target,$this->getDamage());

        $this->fighterManager->decreaseStrength($attacker, self::STRENGTH_NEED);

        $this->report = $this->twig->render('game/report/punch.html.twig', [
            'damages' => $this->getDamage(),
            'publicName' => $target->getPublicName(),
            'KO' => 0 === $target->getHealth(),
        ]);

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/punch.html.twig', [
                'target' => $target,
                'attacker' => $attacker,
                'damages' => $this->getDamage(),
            ]),
            $from,
            -($this->getDamage())
        );

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/punch.html.twig', [
                'target' => $target,
                'attacker' => $attacker,
                'damages' => $this->getDamage(),
            ]),
            $to,
            -($this->getDamage())
        );

        if (0 === $target->getHealth()) {
            $this->fighterManager->createEvent(
                $this->twig->render('game/event/kill.html.twig', [
                    'target' => $target,
                    'attacker' => $attacker,
                ]),
                $from
            );
        }
    }
}
