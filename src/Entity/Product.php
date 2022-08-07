<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Le nom du produit est obligatoire"
    )]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "Le nom du produit doit faire au moins {{ limit }} caractères",
        maxMessage: "Le nom du produit doit faire au maximum {{ limit }} caractères"
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    #[Assert\NotNull(
        message: "Le prix du produit est obligatoire"
    )]
    #[Assert\GreaterThan(
        value: 0,
        message: "Le prix du produit doit être supérieur à 0"
    )]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: "La description du produit est obligatoire"
    )]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "La description du produit doit faire au moins {{ limit }} caractères",
        maxMessage: "La description du produit doit faire au maximum {{ limit }} caractères"
    )]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isOnline = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Photo::class)]
    private Collection $photos;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductInCart::class)]
    private Collection $productInCarts;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->productInCarts = new ArrayCollection();
    }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductInCart>
     */
    public function getProductInCarts(): Collection
    {
        return $this->productInCarts;
    }

    public function addProductInCart(ProductInCart $productInCart): self
    {
        if (!$this->productInCarts->contains($productInCart)) {
            $this->productInCarts->add($productInCart);
            $productInCart->setProduct($this);
        }

        return $this;
    }

    public function removeProductInCart(ProductInCart $productInCart): self
    {
        if ($this->productInCarts->removeElement($productInCart)) {
            // set the owning side to null (unless already changed)
            if ($productInCart->getProduct() === $this) {
                $productInCart->setProduct(null);
            }
        }

        return $this;
    }
}
