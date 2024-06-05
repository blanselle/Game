<?php

namespace App\Entity;

use App\Entity\Trait\ItemTrait;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    const ITEM_TYPE_ONE_HAND = 'one_hand';
    const ITEM_TYPE_TWO_HAND = 'two_hands';
    const ITEM_TYPE_SHIELD = 'shield';

    const ITEM_POSITION_RIGHT_HAND = 'right_hand';
    const ITEM_POSITION_LEFT_HAND = 'left_hand';
    const ITEM_POSITION_TWO_HAND = 'two_hands';

    use TimestampableEntity;
    use ItemTrait;

    #[ORM\Column(nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?string $position = null;

    #[ORM\Column]
    private int $damage = 0;

    #[ORM\Column]
    private int $armor = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'weapons')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column]
    private bool $worn = false;

    #[ORM\Column]
    // Portée
    private int $shootingRange = 1;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Equipment
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): Equipment
    {
        $this->position = $position;

        return $this;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function setDamage(int $damage): Equipment
    {
        $this->damage = $damage;

        return $this;
    }

    public function getArmor(): int
    {
        return $this->armor;
    }

    public function setArmor(int $armor): Equipment
    {
        $this->armor = $armor;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Equipment
    {
        $this->user = $user;

        return $this;
    }

    public function isWorn(): bool
    {
        return $this->worn;
    }

    public function setWorn(bool $worn): Equipment
    {
        $this->worn = $worn;

        return $this;
    }

    public function getShootingRange(): int
    {
        return $this->shootingRange;
    }

    public function setShootingRange(int $shootingRange): Equipment
    {
        $this->shootingRange = $shootingRange;

        return $this;
    }
}
