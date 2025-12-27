<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
#[ORM\Table(name: 'orders')]
class Orders
{
    public const STATE_PENDING = 'PENDING';
    public const STATE_FINISHED = 'FINISHED';
    public const STATE_CANCELLED = 'CANCELLED';
    
    public const RECEPTION_DELIVERY = 'DELIVERY';
    public const RECEPTION_PICK_UP = 'PICK_UP';
    public const RECEPTION_AT_THE_RESTAURANT = 'AT_THE_RESTAURANT';
    
    public const DELIVERY_STATUS_PENDING = 'pending';
    public const DELIVERY_STATUS_ONGOING = 'ongoing';
    public const DELIVERY_STATUS_DELIVERED = 'delivered';
    public const DELIVERY_STATUS_CANCELLED = 'cancelled';
    public static function getReceptionTypes(): array
    {
        return [
            self::RECEPTION_DELIVERY => 'Livraison',
            self::RECEPTION_PICK_UP => 'À emporter',
            self::RECEPTION_AT_THE_RESTAURANT => 'Sur place',
        ];
    }

    public static function getDeliveryStatuses(): array
    {
        return [
            self::DELIVERY_STATUS_PENDING => 'En attente',
            self::DELIVERY_STATUS_ONGOING => 'En cours',
            self::DELIVERY_STATUS_DELIVERED => 'Livrée',
            self::DELIVERY_STATUS_CANCELLED => 'Annulée',
        ];
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "total_price", type: "float")]
    private ?float $totalPrice = null;

    #[ORM\Column(name: "order_date", type: "datetime_immutable")]
    private ?\DateTimeImmutable $orderDate = null;

    #[ORM\Column(name: "order_state", type: "string", length: 100)]
    private ?string $orderState = null;

    #[ORM\Column(name: "reception_type", type: "string", length: 100)]
    private ?string $receptionType = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id", nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(name: "zone_id", referencedColumnName: "id", nullable: true)]
    private ?Zone $zone = null;

    /**
     * @var Collection<int, Extra>
     */
    #[ORM\ManyToMany(targetEntity: Extra::class, inversedBy: 'orders')]
    #[ORM\JoinTable(name: 'orders_extras')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'extra_id', referencedColumnName: 'id')]
    private Collection $extra;

    /**
     * @var Collection<int, OrderMenu>
     */
    #[ORM\OneToMany(targetEntity: OrderMenu::class, mappedBy: 'orders')]
    #[ORM\JoinTable(name: 'order_menu')]
    private Collection $orderMenus;

    /**
     * @var Collection<int, OrderBurger>
     */
    #[ORM\OneToMany(targetEntity: OrderBurger::class, mappedBy: 'orders')]
    #[ORM\JoinTable(name: 'order_burger')]
    private Collection $orderBurgers;

    #[ORM\ManyToOne(targetEntity: DeliveryGuy::class, inversedBy: 'deliveries')]
    #[ORM\JoinColumn(name: 'delivery_guy_id', referencedColumnName: 'id')]
    private ?DeliveryGuy $deliveryGuy = null;

    #[ORM\Column(name: 'delivery_status', type: 'string', length: 50, nullable: true)]
    private ?string $deliveryStatus = null;

    #[ORM\Column(name: 'delivery_rating', type: 'float', nullable: true)]
    private ?float $deliveryRating = null;
 
    #[ORM\Column(name: 'delivery_review', type: 'text', nullable: true)]
    private ?string $deliveryReview = null;

    #[ORM\Column(name: 'delivery_started_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveryStartedAt = null;

    #[ORM\Column(name: 'delivery_completed_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveryCompletedAt = null;

    #[ORM\OneToOne(mappedBy: 'orders', targetEntity: Payment::class)]
    private ?Payment $payment = null;

    public function __construct()
    {
        $this->extra = new ArrayCollection();
        $this->orderMenus = new ArrayCollection();
        $this->orderBurgers = new ArrayCollection();
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeImmutable $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getOrderState(): ?string
    {
        return $this->orderState;
    }

    public function setOrderState(string $orderState): static
    {
        $this->orderState = $orderState;

        return $this;
    }

    public function getReceptionType(): ?string
    {
        return $this->receptionType;
    }

    public function setReceptionType(string $receptionType): static
    {
        $this->receptionType = $receptionType;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return Collection<int, Extra>
     */
    public function getExtra(): Collection
    {
        return $this->extra;
    }

    public function addExtra(Extra $extra): static
    {
        if (!$this->extra->contains($extra)) {
            $this->extra->add($extra);
            $extra->addOrder($this);
        }

        return $this;
    }

    public function removeExtra(Extra $extra): static
    {
        if ($this->extra->removeElement($extra)) {
            $extra->removeOrder($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderMenu>
     */
    public function getOrderMenus(): Collection
    {
        return $this->orderMenus;
    }

    public function addOrderMenu(OrderMenu $orderMenu): static
    {
        if (!$this->orderMenus->contains($orderMenu)) {
            $this->orderMenus->add($orderMenu);
            $orderMenu->setOrder($this);
        }

        return $this;
    }

    public function removeOrderMenu(OrderMenu $orderMenu): static
    {
        if ($this->orderMenus->removeElement($orderMenu)) {
            // set the owning side to null (unless already changed)
            if ($orderMenu->getOrder() === $this) {
                $orderMenu->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderBurger>
     */
    public function getOrderBurgers(): Collection
    {
        return $this->orderBurgers;
    }

    public function addOrderBurger(OrderBurger $orderBurger): static
    {
        if (!$this->orderBurgers->contains($orderBurger)) {
            $this->orderBurgers->add($orderBurger);
            $orderBurger->setOrder($this);
        }

        return $this;
    }

    public function removeOrderBurger(OrderBurger $orderBurger): static
    {
        if ($this->orderBurgers->removeElement($orderBurger)) {
            // set the owning side to null (unless already changed)
            if ($orderBurger->getOrder() === $this) {
                $orderBurger->setOrder(null);
            }
        }

        return $this;
    }

        public function getDeliveryGuy(): ?DeliveryGuy
    {
        return $this->deliveryGuy;
    }

    public function setDeliveryGuy(?DeliveryGuy $deliveryGuy): static
    {
        $this->deliveryGuy = $deliveryGuy;

        return $this;
    }

    public function getDeliveryStatus(): ?string
    {
        return $this->deliveryStatus;
    }

    public function setDeliveryStatus(?string $deliveryStatus): static
    {
        $this->deliveryStatus = $deliveryStatus;

        return $this;
    }

    public function getDeliveryRating(): ?float
    {
        return $this->deliveryRating;
    }

    public function setDeliveryRating(?float $deliveryRating): static
    {
        $this->deliveryRating = $deliveryRating;

        return $this;
    }

    public function getDeliveryReview(): ?string
    {
        return $this->deliveryReview;
    }

    public function setDeliveryReview(?string $deliveryReview): static
    {
        $this->deliveryReview = $deliveryReview;

        return $this;
    }

    public function getDeliveryStartedAt(): ?\DateTimeImmutable
    {
        return $this->deliveryStartedAt;
    }

    public function setDeliveryStartedAt(?\DateTimeImmutable $deliveryStartedAt): static
    {
        $this->deliveryStartedAt = $deliveryStartedAt;

        return $this;
    }

    public function getDeliveryCompletedAt(): ?\DateTimeImmutable
    {
        return $this->deliveryCompletedAt;
    }

    public function setDeliveryCompletedAt(?\DateTimeImmutable $deliveryCompletedAt): static
    {
        $this->deliveryCompletedAt = $deliveryCompletedAt;

        return $this;
    }
    // Méthode pour calculer le prix total à partir des menus et burgers
    public function calculateTotalPrice(): float
    {
        $total = 0;
        
        // Calculer le total des menus
        foreach ($this->orderMenus as $orderMenu) {
            if ($orderMenu->getMenu()) {
                $total += $orderMenu->getMenu()->getPrice() * $orderMenu->getQuantity();
            }
        }
        
        // Calculer le total des burgers
        foreach ($this->orderBurgers as $orderBurger) {
            if ($orderBurger->getBurger()) {
                $total += $orderBurger->getBurger()->getPrice() * $orderBurger->getQuantity();
            }
        }
        
        // Ajouter le prix des extras
        foreach ($this->extra as $extra) {
            $total += $extra->getPrice();
        }
        
        return $total;
    }
}