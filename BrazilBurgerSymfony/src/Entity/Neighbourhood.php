<?php

namespace App\Entity;

use App\Repository\NeighbourhoodRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeighbourhoodRepository::class)]
#[ORM\Table(name: 'neighbourhood')]
class Neighbourhood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id")]
    private ?int $id = null;

    #[ORM\Column(name: "nom", length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'neighbourhoods')]
    #[ORM\JoinColumn(name: "zone_id", nullable: false)]
    private ?Zone $zone = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }
}
