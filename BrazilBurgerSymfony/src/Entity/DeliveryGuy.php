<?php

namespace App\Entity;

use App\Repository\DeliveryGuyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DeliveryGuyRepository::class)]
#[ORM\Table(name: 'deliveryguy')]

class DeliveryGuy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'deliveryGuy', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\OneToMany(targetEntity: Orders::class, mappedBy: 'deliveryGuy')]
    private Collection $deliveries;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Orders $delivery): static
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries->add($delivery);
            $delivery->setDeliveryGuy($this);
        }

        return $this;
    }

    public function removeDelivery(Orders $delivery): static
    {
        if ($this->deliveries->removeElement($delivery)) {
            // set the owning side to null (unless already changed)
            if ($delivery->getDeliveryGuy() === $this) {
                $delivery->setDeliveryGuy(null);
            }
        }

        return $this;
    }   
}
