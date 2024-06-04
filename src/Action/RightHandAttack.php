<?php

namespace App\Action;

use App\Entity\Position;

class RightHandAttack extends AbstractAction
{
    const int STRENGTH_NEED = 5;
    const int DAMAGE = 5;

    private int $damage;

    public function getIdentifier(): string
    {
        return 'RightHandAttack';
    }

    public function getName(): string
    {
        return 'Attaque main droite';
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
            && $from->getFighter()->getRightHandWeapon()
            && $from->getFighter()->getRightHandWeapon()->getShootingRange() >= $this->getDistance($from, $to)
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

        $damage = self::DAMAGE + $attacker->getRightHandWeapon()->getDamage() - $to->getFighter()->getArmorLevel();
        if (0 > $damage) {
            $damage = 0;
        }

        $this->setDamage($damage);
        $this->fighterManager->applyDamage($target, $this->getDamage());

        $this->report = $this->twig->render('game/report/rightHandAttack.html.twig', [
            'damages' => $this->getDamage(),
            'attacker' => $from->getFighter(),
            'publicName' => $target->getPublicName(),
            'KO' => 0 === $target->getHealth(),
        ]);

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/rightHandAttack.html.twig', [
                'target' => $target,
                'attacker' => $attacker,
                'damages' => $this->getDamage(),
            ]),
            $from,
            -($this->getDamage())
        );

        $this->fighterManager->createEvent(
            $this->twig->render('game/event/rightHandAttack.html.twig', [
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
