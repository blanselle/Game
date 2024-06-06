<?php

namespace App\Action;

use App\Entity\Position;
use App\Enum\Equipment\WeaponPosition;

class TwoHandsAttack extends AbstractAction
{
    const int STRENGTH_NEED = 5;

    private int $damage;

    public function getIdentifier(): string
    {
        return 'TwoHandsAttack';
    }

    public function getName(): string
    {
        return 'Attaque à deux mains';
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
            && $from->getFighter()->getTwoHandsWeapon()
            && $from->getFighter()->getTwoHandsWeapon()->getShootingRange() >= $this->getDistance($from, $to)
            && 0 < $this->getDistance($from, $to)
            && $from->getFighter()->getStrength() >= self::STRENGTH_NEED;
    }

    public function run(Position $from, Position $to): void
    {
        if (!$this->support($from, $to)) {
            return;
        }

        $target = $to->getFighter();
        $attacker = $from->getFighter();

        $this->fighterManager->decreaseStrength($attacker, self::STRENGTH_NEED);

        $damage = floor($from->getFighter()->getStrength() / 10)
            + $attacker->getTwoHandsWeapon()->getDamage();

        if (0 > $damage) {
            $damage = 0;
        }

        $this->setDamage($damage);
        $this->fighterManager->applyDamage($target, $this->getDamage());

        $this->report = $this->twig->render('game/report/twoHandsAttack.html.twig', [
            'damages' => $this->getDamage(),
            'attacker' => $from->getFighter(),
            'publicName' => $target->getPublicName(),
            'KO' => 0 === $target->getHealth(),
        ]);

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/twoHandsAttack.html.twig', [
                'target' => $target,
                'attacker' => $attacker,
                'damages' => $this->getDamage(),
            ]),
            $from,
            -($this->getDamage())
        );

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/twoHandsAttack.html.twig', [
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
