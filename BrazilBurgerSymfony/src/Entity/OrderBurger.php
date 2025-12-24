<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "order_burger")] // Nom exact dans ta base Neon
class OrderBurger
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Orders::class)]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "id", nullable: false)]
    private ?Orders $order = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Burger::class)]
    #[ORM\JoinColumn(name: "burger_id", referencedColumnName: "id", nullable: false)]
    private ?Burger $burger = null;

    #[ORM\Column(type: "integer")]
    private ?int $quantity = null;

    public function getOrder(): ?Orders
    {
        return $this->order;
    }

    public function setOrder(?Orders $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}