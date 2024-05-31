<?php

namespace App\Manager;

use App\Entity\FighterInterface;
use App\Entity\Position;
use App\Entity\User;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;

class FighterManager
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function applyDamage(FighterInterface $fighter, int $damage): void
    {
        $fighter->setHealth($fighter->getHealth() - $damage);
        $this->em->flush();

        if (0 === $fighter->getHealth()) {
            $this->removeFromMap($fighter);
        }
    }

    public function removeFromMap(FighterInterface $fighter): void
    {
        $fighter->setPosition(null);
        $this->em->flush();
    }

    public function decreaseStrength(FighterInterface $fighter, $strengthNeeds)
    {
        $fighter->setStrength($fighter->getStrength() - $strengthNeeds);
        $this->em->flush();
    }

    public function move(FighterInterface $fighter, Position $to): void
    {
        $fighter->setPosition($to);
        $this->em->flush();
    }

    public function resurrect(User $user): User
    {
        $user->setHealth($user->getHealthMax());
        $user->setStrength($user->getStrengthMax());
        $user->setStamina($user->getStaminaMax());
        $user->setPosition($this->findResurrectPosition());
        $this->em->flush();

        return $user;
    }

    public function findResurrectPosition(): Position
    {
        /** @var PositionRepository $positionRepository */
        $positionRepository = $this->em->getRepository(Position::class);

        return $positionRepository->getFreePositionArround($positionRepository->getResurrectPosition());
    }
}
