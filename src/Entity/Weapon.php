<?php

namespace App\Entity;

use App\Entity\Trait\ItemTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity]
class Weapon
{
    const ITEM_TYPE_ONE_HAND = 'one_hand';
    const ITEM_TYPE_TWO_HAND = 'two_hand';
    const ITEM_TYPE_SHIELD = 'shield';

    const ITEM_POSITION_RIGHT_HAND = 'right_hand';
    const ITEM_POSITION_LEFT_HAND = 'left_hand';

    use TimestampableEntity;
    use ItemTrait;

    #[ORM\Column]
    private string $type = self::ITEM_TYPE_ONE_HAND;

    #[ORM\Column]
    private string $position = self::ITEM_POSITION_RIGHT_HAND;

    #[ORM\Column]
    private int $damage = 0;

    #[ORM\Column]
    private int $attack = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'weapons')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column]
    private bool $worn = false;

    #[ORM\Column]
    private int $shootingRange = 1;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Weapon
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): Weapon
    {
        $this->position = $position;

        return $this;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function setDamage(int $damage): Weapon
    {
        $this->damage = $damage;

        return $this;
    }

    public function getAttack(): int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): Weapon
    {
        $this->attack = $attack;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Weapon
    {
        $this->user = $user;

        return $this;
    }

    public function isWorn(): bool
    {
        return $this->worn;
    }

    public function setWorn(bool $worn): Weapon
    {
        $this->worn = $worn;

        return $this;
    }

    public function getShootingRange(): int
    {
        return $this->shootingRange;
    }

    public function setShootingRange(int $shootingRange): Weapon
    {
        $this->shootingRange = $shootingRange;

        return $this;
    }
}
