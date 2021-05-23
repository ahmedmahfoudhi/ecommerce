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


}
