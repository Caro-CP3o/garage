<?php

namespace App\Entity;

use App\Entity\Cars;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column]
    private ?int $founded = null;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getFounded(): ?int
    {
        return $this->founded;
    }

    public function setFounded(int $founded): static
    {
        $this->founded = $founded;

        return $this;
    }
    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Cars::class)]
    private Collection $cars;
    
    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    /**
     * @return Collection|Cars[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }
}
