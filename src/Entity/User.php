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

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface, FighterInterface
{
    const MAX_WORN_WEAPON = 2;

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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Weapon::class)]
    private Collection $weapons;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Armor::class)]
    private Collection $armors;

    #[ORM\Column]
    private ?string $imgPath = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->armors = new ArrayCollection();
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

    public function getWeapons(): Collection
    {
        return $this->weapons;
    }

    public function setWeapons(Collection $weapons): User
    {
        $this->weapons = $weapons;

        return $this;
    }

    public function addWeapon(Weapon $weapon): User
    {
        if ($this->getWeapons()->count() >= self::MAX_WORN_WEAPON) {
            return $this;
        }

        if($this->weapons->contains($weapon)) {
            $this->weapons->removeElement($weapon);
        }

        $weapon->setUser($this);

        return $this;
    }
    public function removeWeapon(Weapon $weapon): User
    {
        if($this->weapons->contains($weapon)) {
            $this->weapons->removeElement($weapon);
        }

        $weapon->setUser(null);

        return $this;
    }

    public function getArmors(): Collection
    {
        return $this->armors;
    }

    public function setArmors(Collection $armors): User
    {
        $this->armors = $armors;

        return $this;
    }

    public function addArmor(Armor $armor): self
    {
        if (!$this->armors->contains($armor)) {
            $this->armors->add($armor);
        }

        $armor->setUser($this);

        return $this;
    }

    public function removeArmor(Armor $armor): self
    {
        if ($this->armors->contains($armor)) {
            $this->armors->removeElement($armor);
        }

        $armor->setUser(null);

        return $this;
    }

    public function getFreeHandsCount(): int
    {
        $freeHandsCount = self::MAX_WORN_WEAPON;
        /** @var Weapon $weapon */
        foreach ($this->getWeapons() as $weapon) {
            if (0 === $freeHandsCount) {
                continue;
            }

            if ($weapon->isWorn()) {
                $freeHandsCount--;
            }
        }

        return $freeHandsCount;
    }

    public function getRightHandWeapon(): ?Weapon
    {
        /** @var Weapon $weapon */
        foreach ($this->getWeapons() as $weapon) {
            if ($weapon->isWorn() && Weapon::ITEM_POSITION_RIGHT_HAND === $weapon->getPosition()) {
                return $weapon;
            }
        }

        return null;
    }
    public function getArmorLevel(): int
    {
        $armorLevel = 0;

        /** @var Armor $armor */
        foreach ($this->getArmors() as $armor) {
            if (!$armor->isWorn()) {
                continue;
            }

            $armorLevel += $armor->getArmor();
        }

        return $armorLevel;
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
}
