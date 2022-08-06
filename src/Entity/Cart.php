<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $validatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?paymentMethod $paymentMethod = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: ProductInCart::class)]
    private Collection $productInCarts;

    public function __construct()
    {
        $this->productInCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeInterface
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(?\DateTimeInterface $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getPaymentMethod(): ?paymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?paymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $productInCart->setCart($this);
        }

        return $this;
    }

    public function removeProductInCart(ProductInCart $productInCart): self
    {
        if ($this->productInCarts->removeElement($productInCart)) {
            // set the owning side to null (unless already changed)
            if ($productInCart->getCart() === $this) {
                $productInCart->setCart(null);
            }
        }

        return $this;
    }
}
