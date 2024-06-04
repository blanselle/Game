<?php

namespace App\Entity;

use App\Entity\Trait\ItemTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity]
class Armor
{
    const ITEM_TYPE_HEAVY = 'heavy';
    const ITEM_TYPE_LIGHTWEIGHT = 'lightweight';

    const ITEM_POSITION_HEAD = 'head';
    const ITEM_POSITION_CHEST = 'chest';
    const ITEM_POSITION_BELT = 'belt';
    const ITEM_POSITION_LEGS = 'legs';
    const ITEM_POSITION_FEET = 'feet';

    use TimestampableEntity;
    use ItemTrait;

    #[ORM\Column]
    private string $type = self::ITEM_TYPE_LIGHTWEIGHT;

    #[ORM\Column]
    private string $position = self::ITEM_POSITION_HEAD;

    #[ORM\Column]
    private int $armor = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'armors')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column]
    private bool $worn = false;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Armor
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): Armor
    {
        $this->position = $position;

        return $this;
    }

    public function getArmor(): int
    {
        return $this->armor;
    }

    public function setArmor(int $armor): Armor
    {
        $this->armor = $armor;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Armor
    {
        $this->user = $user;

        return $this;
    }

    public function isWorn(): bool
    {
        return $this->worn;
    }

    public function setWorn(bool $worn): Armor
    {
        $this->worn = $worn;

        return $this;
    }
}
