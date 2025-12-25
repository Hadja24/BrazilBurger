<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_menu')] // Nom exact dans ta base Neon
class OrderMenu
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'orderMenus')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false)]
    private ?Orders $order = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'orderMenus')]
    #[ORM\JoinColumn(name: 'menu_id', referencedColumnName: 'id', nullable: false)]
    private ?Menu $menu = null;

    #[ORM\Column(type: 'integer')]
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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

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
