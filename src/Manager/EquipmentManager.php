<?php

namespace App\Manager;

use App\Entity\Equipment;
use App\Entity\FighterInterface;
use App\Entity\Position;
use App\Enum\Equipment\ArmorPosition;
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
            case WeaponType::oneHand->value:
            case WeaponType::shield->value:
                return [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value];
            case WeaponType::twoHands->value:
                return [WeaponPosition::twoHands->value];
        }

        return [];
    }

    private function getAvailablePositionForArmorType(string $type): array
    {
        if (!in_array($type, array_values(WeaponType::values()))) {
            return [];
        }

        switch ($type) {
            case WeaponType::oneHand->value:
            case WeaponType::shield->value:
                return [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value];
            case WeaponType::twoHands->value:
                return [WeaponPosition::twoHands->value];
        }

        return [];
    }

    public function equip(FighterInterface $fighter, Equipment $equipment, string $position): void
    {
        if (!in_array($position, $equipment->getAvailablePositions())) {
            return;
        }

        $weaponsToRemove = [];
        if (WeaponPosition::twoHands->value === $position) {
            $weaponsToRemove = $this->equipmentRepository->getWornEquipementForUserAndPositions(
                $fighter,
                [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value, WeaponPosition::twoHands->value]
            );
        }

        if (in_array($position, [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value])) {
            $weaponsToRemove = $this->equipmentRepository->getWornEquipementForUserAndPositions(
                $fighter,
                [$position, WeaponPosition::twoHands->value]
            );
        }

        if (in_array(
            $position,
            ArmorPosition::values())
        ) {
            $weaponsToRemove = $this->equipmentRepository->getWornEquipementForUserAndPositions(
                $fighter,
                [$position]
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

    public function dropArmors(FighterInterface $fighter)
    {
        /** @var Equipment $armor */
        foreach ($fighter->getWornArmors() as $armor) {
            $armor->setPosition(null);
            $armor->setWorn(false);
        }

        $this->equipmentRepository->save();
    }
}
