<?php

namespace App\Entity\Trait;

use App\Entity\FighterInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait ItemTrait
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $encumbrance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        
        return $this;
    }

    public function getEncumbrance(): ?int
    {
        return $this->encumbrance;
    }

    public function setEncumbrance(?int $encumbrance): self
    {
        $this->encumbrance = $encumbrance;
        
        return $this;
    }
}
