<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Npc::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: true)]
    private $npc;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'event_viewer')]
    private Collection $viewers;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $result = null;

    public function __construct()
    {
        $this->viewers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Event
    {
        $this->id = $id;
        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): Event
    {
        $this->body = $body;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user): Event
    {
        $this->user = $user;

        return $this;
    }

    public function getNpc()
    {
        return $this->npc;
    }

    public function setNpc(FighterInterface $npc)
    {
        $this->npc = $npc;

        return $this;
    }

    public function getViewers(): Collection
    {
        return $this->viewers;
    }

    public function setViewers(Collection $viewers): Event
    {
        $this->viewers = $viewers;

        return $this;
    }

    public function addViewer(User $user): Event
    {
        if(!$this->viewers->contains($user)) {
            $this->viewers->add($user);
        }

        return $this;
    }

    public function removeViewer(User $user): Event
    {
        if($this->viewers->contains($user)) {
            $this->viewers->removeElement($user);
        }

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(?int $result): Event
    {
        $this->result = $result;

        return $this;
    }
}
