<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Command
 *
 * @ORM\Table(name="command", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Command
{
    /**
     * @var int
     *
     * @ORM\Column(name="commandid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $commandid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="command_date", type="date", nullable=false)
     */
    private $commandDate;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getCommandid(): ?int
    {
        return $this->commandid;
    }

    public function getCommandDate(): ?\DateTimeInterface
    {
        return $this->commandDate;
    }

    public function setCommandDate(\DateTimeInterface $commandDate): self
    {
        $this->commandDate = $commandDate;

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


}
