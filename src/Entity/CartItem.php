<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @ORM\Table(name="cart_item", indexes={@ORM\Index(name="product_id", columns={"product_id"}), @ORM\Index(name="shopping_cart_id", columns={"shopping_cart_id"})})
 * @ORM\Entity
 */
class CartItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="cart_item_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cartItemId;

    /**
     * @var int
     *
     * @ORM\Column(name="cart_item_qte", type="integer", nullable=false)
     */
    private $cartItemQte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addedAt", type="date", nullable=false)
     */
    private $addedat;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * })
     */
    private $product;

    /**
     * @var \ShoppingCart
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shopping_cart_id", referencedColumnName="shopping_cart_id")
     * })
     */
    private $shoppingCart;

    public function getCartItemId(): ?int
    {
        return $this->cartItemId;
    }

    public function getCartItemQte(): ?int
    {
        return $this->cartItemQte;
    }

    public function setCartItemQte(int $cartItemQte): self
    {
        $this->cartItemQte = $cartItemQte;

        return $this;
    }

    public function getAddedat(): ?\DateTimeInterface
    {
        return $this->addedat;
    }

    public function setAddedat(\DateTimeInterface $addedat): self
    {
        $this->addedat = $addedat;

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

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart(?ShoppingCart $shoppingCart): self
    {
        $this->shoppingCart = $shoppingCart;

        return $this;
    }


}
