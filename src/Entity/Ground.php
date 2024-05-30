<?php

namespace App\Entity;

use App\Entity\Trait\PrimaryAttributeTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class Ground
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'ground', targetEntity: Position::class)]
    private Collection $positions;

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

}
