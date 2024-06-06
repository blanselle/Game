<?php

namespace App\Entity;

use App\Entity\Trait\PrimaryAttributeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'npc', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\Column]
    private ?string $imgPath = null;

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
        return sprintf('%s (%d)', $this->getName(), $this->getId());
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function setEvents(Collection $events): Npc
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(Event $event): Npc
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }

        $event->setNpc($this);

        return $this;
    }

    public function removeEvent(Event $event): Npc
    {
        if($this->events->contains($event)) {
            $this->events->removeElement($event);
        }

        $event->setNpc(null);

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): Npc
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getEquipments(): Collection
    {
        return new ArrayCollection();
    }

    public function setEquipments(Collection $equipments): self
    {
        // TODO: Implement setEquipments() method.
    }

    public function addEquipment(Equipment $equipment): self
    {
        // TODO: Implement addEquipment() method.
    }

    public function removeEquipment(Equipment $equipment): self
    {
        // TODO: Implement removeEquipment() method.
    }

    public function hasAtLeastOneFreeHand(): bool
    {
        return true;
    }

    public function getRightHandWeapon(): ?Equipment
    {
        return null;
    }

    public function getTwoHandsWeapon(): ?Equipment
    {
        return null;
    }

    public function getWornWeapons(): Collection
    {
        return new ArrayCollection();
    }
}
