<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface FighterInterface
{
    public function getPosition(): ?Position;

    public function setPosition(?Position $position): self;

    public function getHealthMax(): int;

    public function setHealthMax(int $healthMax): self;

    public function getHealth(): int;

    public function setHealth(int $health): self;

    public function getStaminaMax(): int;

    public function setStaminaMax(int $staminaMax): self;

    public function getStamina(): int;

    public function setStamina(int $stamina): self;

    public function getStrengthMax(): int;

    public function setStrengthMax(int $strengthMax): self;

    public function getStrength(): int;

    public function setStrength(int $strength): self;

    public function getPublicName(): string;

    public function getImgPath(): ?string;

    public function getEquipments(): Collection;

    public function setEquipments(Collection $equipments): self;

    public function addEquipment(Equipment $equipment): self;

    public function removeEquipment(Equipment $equipment): self;

    public function hasAtLeastOneFreeHand(): bool;

    public function getRightHandWeapon(): ?Equipment;

    public function getTwoHandsWeapon(): ?Equipment;

    public function getWornWeapons(): Collection;

    public function getWornArmors(): Collection;

    public function getArmorLevel(): int;
}
