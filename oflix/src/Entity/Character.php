<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'integer', length: 255)]
    private $gender;

    #[ORM\Column(type: 'text')]
    private $bio;

    #[ORM\Column(type: 'integer')]
    private $age;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'personage', targetEntity: RolePlay::class)]
    private $rolePlays;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->rolePlays = new ArrayCollection();
    }

    /**
     * Si l'on tente de faire un echo sur l'objet Character, PHP retournera la valeur du prénom
     */
    public function __toString()
    {
        return $this->firstname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGenderText(): ?string
    {
        return $this->gender === self::GENDER_MALE ? 'Homme' : 'Femme';
    }


    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, RolePlay>
     */
    public function getRolePlays(): Collection
    {
        return $this->rolePlays;
    }

    public function addRolePlay(RolePlay $rolePlay): self
    {
        if (!$this->rolePlays->contains($rolePlay)) {
            $this->rolePlays[] = $rolePlay;
            $rolePlay->setPersonage($this);
        }

        return $this;
    }

    public function removeRolePlay(RolePlay $rolePlay): self
    {
        if ($this->rolePlays->removeElement($rolePlay)) {
            // set the owning side to null (unless already changed)
            if ($rolePlay->getPersonage() === $this) {
                $rolePlay->setPersonage(null);
            }
        }

        return $this;
    }
}
