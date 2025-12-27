<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\Table(name: 'customer')]
#[ORM\HasLifecycleCallbacks]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: "integer")]
    private ?int $id = null;

    // Ajoutez ces propriétés pour la synchronisation
    #[ORM\Column(name: "name", type: "string", length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: "surname", type: "string", length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(name: "phone", type: "string", length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(name: "email", type: "string", length: 150, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "account_id", referencedColumnName: "id", nullable: false)]
    private ?Account $account = null;

    /**
     * @var Collection<int, Orders>
     */
    #[ORM\OneToMany(targetEntity: Orders::class, mappedBy: 'customer')]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name ?? $this->account?->getName();
    }

    public function getSurname(): ?string
    {
        return $this->surname ?? $this->account?->getSurname();
    }

    public function getPhone(): ?string
    {
        return $this->phone ?? $this->account?->getPhone();
    }

    public function getEmail(): ?string
    {
        return $this->email ?? $this->account?->getEmail();
    }

    public function getPassword(): ?string
    {
        return $this->password ?? $this->account?->getPassword();
    }

    public function getFullName(): string
    {
        return ($this->getName() ?? '') . ' ' . ($this->getSurname() ?? '');
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
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): static
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }
}