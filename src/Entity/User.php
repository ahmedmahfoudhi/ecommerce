<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=70, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=70, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="text", length=65535, nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="text", length=65535, nullable=false)
     */
    private $street;

    /**
     * @var int
     *
     * @ORM\Column(name="postal_code", type="integer", nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="text", length=65535, nullable=false)
     */
    private $phoneNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="role", type="integer", nullable=false)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text", length=65535, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="text", length=65535, nullable=false)
     */
    private $password;

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


}
