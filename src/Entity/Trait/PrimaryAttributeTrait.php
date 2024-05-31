<?php

namespace App\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait PrimaryAttributeTrait
{
    // Points de vie max
    #[ORM\Column(type: Types::INTEGER)]
    private int $healthMax = 100;

    // Points de vie courants
    #[ORM\Column(type: Types::INTEGER)]
    private int $health = 100;

    // Points de magie max
    #[ORM\Column(type: Types::INTEGER)]
    private int $staminaMax = 100;

    // Points de magie courants
    #[ORM\Column(type: Types::INTEGER)]
    private int $stamina = 100;

    // Détermine la capacité de port et les points d'endurance max
    #[ORM\Column(type: Types::INTEGER)]
    private int $strengthMax = 100;

    // Points d'endurance courants
    #[ORM\Column(type: Types::INTEGER)]
    private int $strength = 100;

    public function getHealthMax(): int
    {
        return $this->healthMax;
    }

    public function setHealthMax(int $healthMax): self
    {
        $this->healthMax = $healthMax;

        return $this;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function setHealth(int $health): self
    {
        if ($health < 0) {
            $health = 0;
        }

        $this->health = $health;

        return $this;
    }

    public function getStaminaMax(): int
    {
        return $this->staminaMax;
    }

    public function setStaminaMax(int $staminaMax): self
    {
        $this->staminaMax = $staminaMax;

        return $this;
    }

    public function getStamina(): int
    {
        return $this->stamina;
    }

    public function setStamina(int $stamina): self
    {
        $this->stamina = $stamina;

        return $this;
    }

    public function getStrengthMax(): int
    {
        return $this->strengthMax;
    }

    public function setStrengthMax(int $strengthMax): self
    {
        $this->strengthMax = $strengthMax;
        return $this;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;
        return $this;
    }
}
