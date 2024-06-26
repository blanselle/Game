<?php

namespace App\Entity;

use App\Entity\Trait\PrimaryAttributeTrait;
use App\Enum\Equipment\ArmorType;
use App\Enum\Equipment\WeaponPosition;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface, FighterInterface
{
    use TimestampableEntity;
    use PrimaryAttributeTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?string $username = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Position::class)]
    private ?Position $position = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Equipment::class)]
    private Collection $equipments;

    #[ORM\Column]
    private ?string $imgPath = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->equipments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): User
    {
        $this->username = $username;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): User
    {
        if (null != $position) {
            $position->setUser($this);
        }

        if (null !== $this->getPosition()) {
            $this->getPosition()->setUser(null);
        }

        $this->position = $position;

        return $this;
    }

    public function getPublicName(): string
    {
        return sprintf('%s (%d)', $this->getUsername(), $this->getId());
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function setEvents(Collection $events): User
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(Event $event): User
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }

        $event->setUser($this);

        return $this;
    }

    public function removeEvent(Event $event): User
    {
        if($this->events->contains($event)) {
            $this->events->removeElement($event);
        }

        $event->setUser(null);

        return $this;
    }

    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function setEquipments(Collection $equipments): User
    {
        $this->equipments = $equipments;

        return $this;
    }

    public function addEquipment(Equipment $equipment): self
    {
        if($this->equipments->contains($equipment)) {
            $this->equipments->removeElement($equipment);
        }

        $equipment->setUser($this);

        return $this;
    }

    public function removeEquipment(Equipment $equipment): self
    {
        if($this->equipments->contains($equipment)) {
            $this->equipments->removeElement($equipment);
        }

        $equipment->setUser(null);

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): User
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function hasAtLeastOneFreeHand(): bool
    {
        $countTwoHands = 0;
        $countOneHand = 0;

        /** @var Equipment $equipment */
        foreach ($this->getEquipments() as $equipment) {
            if (!$equipment->isWorn()) {
                continue;
            }

            if (WeaponPosition::twoHands->value === $equipment->getPosition()) {
                $countTwoHands++;
            }

            if (in_array($equipment->getPosition(), [WeaponPosition::leftHand->value, WeaponPosition::rightHand->value])) {
                $countOneHand++;
            }
        }

        return 0 === $countTwoHands && 1 >= $countOneHand;
    }

    public function getTwoHandsWeapon(): ?Equipment
    {
        /** @var Equipment $equipment */
        foreach ($this->getEquipments() as $equipment) {
            if (!$equipment->isWorn()) {
                continue;
            }

            if (WeaponPosition::twoHands->value === $equipment->getPosition()) {
                return $equipment;
            }
        }

        return null;
    }

    public function getRightHandWeapon(): ?Equipment
    {
        /** @var Equipment $equipment */
        foreach ($this->getEquipments() as $equipment) {
            if (!$equipment->isWorn()) {
                continue;
            }

            if (WeaponPosition::rightHand->value === $equipment->getPosition()) {
                return $equipment;
            }
        }

        return null;
    }

    public function getWornWeapons(): Collection
    {
        $weapons = new ArrayCollection();

        foreach ($this->getEquipments() as $equipment) {
            if (!$equipment->isWorn()) {
                continue;
            }

            if (in_array(
                $equipment->getPosition(),
                [WeaponPosition::rightHand->value, WeaponPosition::leftHand->value, WeaponPosition::twoHands->value])
            ) {
                $weapons->add($equipment);
            }
        }

        return $weapons;
    }

    public function getWornArmors(): Collection
    {
        $armors = new ArrayCollection();

        foreach ($this->getEquipments() as $equipment) {
            if (!$equipment->isWorn()) {
                continue;
            }

            if (in_array(
                $equipment->getType(),
                [ArmorType::heavy->value, ArmorType::lightweight->value])
            ) {
                $armors->add($equipment);
            }
        }

        return $armors;
    }

    public function getArmorLevel(): int
    {
        $armorLevel = 0;

        /** @var Equipment $equipment */
        foreach ($this->getEquipments() as $equipment) {
            if ($equipment->isWorn()) {
                $armorLevel += $equipment->getArmor();
            }
        }

        return $armorLevel;
    }
}
