<?php

namespace App\Manager;

use App\Entity\Equipment;
use App\Entity\FighterInterface;
use App\Entity\Position;
use App\Enum\Equipment\WeaponPosition;
use App\Enum\Equipment\WeaponType;
use App\Repository\EquipmentRepository;

class EquipmentManager
{
    public function __construct(private EquipmentRepository $equipmentRepository)
    {
    }

    private function getAvailablePositionForWeaponType(string $type): array
    {
        if (!in_array($type, array_values(WeaponType::values()))) {
            return [];
        }

        switch ($type) {
            case WeaponType::OneHand->value:
            case WeaponType::Shield->value:
                return [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value];
            case WeaponType::TwoHand->value:
                return [WeaponPosition::twoHands->value];
        }

        return [];
    }

    public function equip(FighterInterface $fighter, Equipment $equipment, ?string $position): void
    {
        if (null !== $position && !in_array($position, $this->getAvailablePositionForWeaponType($equipment->getType()))) {
            return;
        }

        $weaponsToRemove = [];
        if (WeaponType::TwoHand->value === $equipment->getType()) {
            $weaponsToRemove = $this->equipmentRepository->getWornEquipementForUserAndPositions(
                $fighter,
                [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value, WeaponPosition::twoHands->value]
            );

            $position = WeaponPosition::twoHands->value;
        }

        if (WeaponType::OneHand->value === $equipment->getType()) {
            if (is_null($position)) {
                $position = WeaponPosition::rightHand->value;
            }

            $weaponsToRemove = $this->equipmentRepository->getWornEquipementForUserAndPositions(
                $fighter,
                [$position, WeaponPosition::twoHands->value]
            );
        }

        if (WeaponType::Shield->value === $equipment->getType()) {
            if (is_null($position)) {
                $position = WeaponPosition::leftHand->value;
            }

            $weaponsToRemove = $this->equipmentRepository->getWornEquipementForUserAndPositions(
                $fighter,
                [$position, WeaponPosition::twoHands->value]
            );
        }

        /** @var Equipment $weapon */
        foreach ($weaponsToRemove as $weapon) {
            $weapon->setPosition(null);
            $weapon->setWorn(false);
        }

        $equipment->setPosition($position);
        $equipment->setWorn(true);

        $this->equipmentRepository->save();
    }

    public function dropWeapons(FighterInterface $fighter)
    {
        /** @var Equipment $weapon */
        foreach ($fighter->getWornWeapons() as $weapon) {
            $weapon->setPosition(null);
            $weapon->setWorn(false);
        }

        $this->equipmentRepository->save();
    }
}
