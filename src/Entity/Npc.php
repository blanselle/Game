<?php

namespace App\Entity;

use App\Entity\Trait\PrimaryAttributeTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity]
class Npc implements FighterInterface
{
    use TimestampableEntity;
    use PrimaryAttributeTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'npc', targetEntity: Position::class)]
    private ?Position $position = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Npc
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Npc
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): Npc
    {
        if (null != $position) {
            $position->setNpc($this);
        }

        if (null !== $this->getPosition()) {
            $this->getPosition()->setNpc(null);
        }

        $this->position = $position;

        return $this;
    }

    public function getPublicName(): string
    {
        return $this->getName();
    }
}
