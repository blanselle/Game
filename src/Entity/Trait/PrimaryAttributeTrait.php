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
    private int $strenghMax = 100;

    // Points d'endurance courants
    #[ORM\Column(type: Types::INTEGER)]
    private int $strengh = 100;

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

    public function getStrenghMax(): int
    {
        return $this->strenghMax;
    }

    public function setStrenghMax(int $strenghMax): self
    {
        $this->strenghMax = $strenghMax;

        return $this;
    }

    public function getStrengh(): int
    {
        return $this->strengh;
    }

    public function setStrengh(int $strengh): self
    {
        $this->strengh = $strengh;

        return $this;
    }
}
