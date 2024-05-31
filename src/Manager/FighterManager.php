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

    public function applyDamage(Position $from, Position $to, int $damage): void
    {
        $to->getFighter()->setHealth($to->getFighter()->getHealth() - $damage);
        $this->em->flush();

        if (0 === $to->getFighter()->getHealth()) {
            $this->removeFromMap($to->getFighter());
        }
    }

    public function removeFromMap(FighterInterface $fighter): void
    {
        $fighter->setPosition(null);
        $this->em->flush();
    }

    public function move(Position $from, Position $to, int $strengthNeeds = 0): void
    {
        $from->getFighter()->setStrength($from->getFighter()->getStrength() - $strengthNeeds);

        $from->getFighter()->setPosition($to);
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
