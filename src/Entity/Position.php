<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
#[UniqueEntity(
    fields: ['x', 'y'],
    message: 'This position already exists',
)]
#[ORM\UniqueConstraint(
    name: 'unique_position',
    columns: ['x', 'y']
)]
class Position
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $x;

    #[ORM\Column]
    private ?int $y;

    #[ORM\OneToOne(inversedBy: 'position', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user;

    #[ORM\OneToOne(inversedBy: 'position', targetEntity: Npc::class)]
    #[ORM\JoinColumn(name: 'npc_id', referencedColumnName: 'id')]
    private ?Npc $npc;

    #[ORM\ManyToOne(targetEntity: Ground::class, inversedBy: 'positions')]
    private ?Ground $ground = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(?int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(?int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Position
    {
        $this->user = $user;

        return $this;
    }

    public function getNpc(): ?Npc
    {
        return $this->npc;
    }

    public function setNpc(?Npc $npc): Position
    {
        $this->npc = $npc;

        return $this;
    }

    public function getGround(): ?Ground
    {
        return $this->ground;
    }

    public function setGround(?Ground $ground): Position
    {
        $this->ground = $ground;

        return $this;
    }

    public function getFighter(): ?FighterInterface
    {
        return $this->getUser() ?? $this->getNpc();
    }
}
