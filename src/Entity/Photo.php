<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Le nom de la photo est obligatoire"
    )]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "Le nom de la photo doit faire au moins {{ limit }} caractères",
        maxMessage: "Le nom de la photo doit faire au maximum {{ limit }} caractères"
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Le texte alternatif de la photo est obligatoire"
    )]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Le texte alternatif de la photo doit faire au moins {{ limit }} caractères",
        maxMessage: "Le texte alternatif de la photo doit faire au maximum {{ limit }} caractères"
    )]
    private ?string $alternativeText = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlternativeText(): ?string
    {
        return $this->alternativeText;
    }

    public function setAlternativeText(string $alternativeText): self
    {
        $this->alternativeText = $alternativeText;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
