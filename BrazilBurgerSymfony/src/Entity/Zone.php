<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ORM\Table(name: 'zones')]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id")]
    private ?int $id = null;


    #[ORM\Column(name: 'prix_livraison', type: Types::FLOAT, precision: 10, scale: 2, nullable: true)]
    private ?float $deliveryPrice = null;

    #[ORM\Column(name: "nom", length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Orders>
     */
    #[ORM\OneToMany(targetEntity: Orders::class, mappedBy: 'zone')]
    private Collection $orders;

    /**
     * @var Collection<int, Neighbourhood>
     */
    #[ORM\OneToMany(targetEntity: Neighbourhood::class, mappedBy: 'zone')]
    private Collection $neighbourhoods;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->neighbourhoods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryPrice(): ?float
    {
        return $this->deliveryPrice;
    }

    public function setDeliveryPrice(float $deliveryPrice): static
    {
        $this->deliveryPrice = $deliveryPrice;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setZone($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getZone() === $this) {
                $order->setZone(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Neighbourhood>
     */
    public function getNeighbourhoods(): Collection
    {
        return $this->neighbourhoods;
    }

    public function addNeighbourhood(Neighbourhood $neighbourhood): static
    {
        if (!$this->neighbourhoods->contains($neighbourhood)) {
            $this->neighbourhoods->add($neighbourhood);
            $neighbourhood->setZone($this);
        }

        return $this;
    }

    public function removeNeighbourhood(Neighbourhood $neighbourhood): static
    {
        if ($this->neighbourhoods->removeElement($neighbourhood)) {
            // set the owning side to null (unless already changed)
            if ($neighbourhood->getZone() === $this) {
                $neighbourhood->setZone(null);
            }
        }

        return $this;
    }
}
