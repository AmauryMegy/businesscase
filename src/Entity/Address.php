<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z_.-]$/",
        message: "Le nom du destinataire doit être uniquement composé de caractères alphabétiques"
    )]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Veuillez indiquer votre rue"
    )]
    private ?string $line1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $line2 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Veuillez indiquer votre ville"
    )]
    private ?string $city = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(
        message: "Veuillez indiquer votre code postal"
    )]
    private ?string $postcode = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(
        pattern: "/^\[0][1-9][0-9]{8}$/",
        message: "Le numéro de téléphone ne peut contenir que 10 chiffres et doit commencer par un 0 suivi d'un numéro entre 1 et 9"
    )]
    private ?string $phoneNumber = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'address')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function setLine1(string $line1): self
    {
        $this->line1 = $line1;

        return $this;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine2(?string $line2): self
    {
        $this->line2 = $line2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addAddress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAddress($this);
        }

        return $this;
    }
}
