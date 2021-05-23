<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Line
 *
 * @ORM\Table(name="line", indexes={@ORM\Index(name="command_id", columns={"command_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class Line
{
    /**
     * @var int
     *
     * @ORM\Column(name="line_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $lineId;

    /**
     * @var int
     *
     * @ORM\Column(name="qte", type="integer", nullable=false)
     */
    private $qte;

    /**
     * @var \Command
     *
     * @ORM\ManyToOne(targetEntity="Command")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="command_id", referencedColumnName="commandID")
     * })
     */
    private $command;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * })
     */
    private $product;


}
