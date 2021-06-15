<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoppingCart
 *
 * @ORM\Table(name="shopping_cart", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class ShoppingCart
{
    /**
     * @var int
     *
     * @ORM\Column(name="shopping_cart_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shoppingCartId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="date", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getShoppingCartId(): ?int
    {
        return $this->shoppingCartId;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime("NOW");
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * @ORM\PreRemove
     */
    public function setDeletedAt(): void
    {
        $this->deletedAt = new \DateTime("NOW");
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


}
