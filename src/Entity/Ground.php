<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity]
class Ground
{
    const string GROUND_WATER = 'water';
    const string GROUND_SANDY = 'sandy';
    const string GROUND_COBBLESTONE = 'cobblestone';

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'ground', targetEntity: Position::class)]
    private Collection $positions;

    #[ORM\Column(nullable: true)]
    private ?string $filePath;

    #[ORM\Column(type: 'boolean')]
    private bool $walkable = true;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Ground
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Ground
    {
        $this->name = $name;

        return $this;
    }

    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function setPositions(Collection $positions): Ground
    {
        $this->positions = $positions;

        return $this;
    }

    public function addPosition(Position $position): self
    {
        if(!$this->positions->contains($position)) {
            $this->positions->add($position);
        }

        $position->setGround($this);

        return $this;
    }

    public function removePosition(Position $position)
    {
        if($this->positions->contains($position)) {
            $this->positions->removeElement($position);
        }

        $position->setGround(null);

        return null;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): Ground
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function isWalkable(): bool
    {
        return $this->walkable;
    }

    public function setWalkable(bool $walkable): Ground
    {
        $this->walkable = $walkable;

        return $this;
    }
}
