<?php

namespace App\Entity;

use App\Repository\WorkshopRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkshopRepository::class)]
class Workshop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Contributor::class, inversedBy: 'workshops')]
    private $contributor;

    #[ORM\ManyToOne(targetEntity: WorkshopCategory::class, inversedBy: 'workshops')]
    private $workshop_category;

    #[ORM\Column(type: 'text')]
    private $info_workshop;

    #[ORM\Column(type: 'text')]
    private $activity_workshop;

    #[ORM\Column(type: 'string', length: 510)]
    private $place;

    #[ORM\Column(type: 'string', length: 255)]
    private $day;

    #[ORM\Column(type: 'string', length: 255)]
    private $hours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContributor(): ?Contributor
    {
        return $this->contributor;
    }

    public function setContributor(?Contributor $contributor): self
    {
        $this->contributor = $contributor;

        return $this;
    }

    public function getWorkshopCategory(): ?WorkshopCategory
    {
        return $this->workshop_category;
    }

    public function setWorkshopCategory(?WorkshopCategory $workshop_category): self
    {
        $this->workshop_category = $workshop_category;

        return $this;
    }

    public function getInfoWorkshop(): ?string
    {
        return $this->info_workshop;
    }

    public function setInfoWorkshop(?string $info_workshop): self
    {
        $this->info_workshop = $info_workshop;

        return $this;
    }

    public function getActivityWorkshop(): ?string
    {
        return $this->activity_workshop;
    }

    public function setActivityWorkshop(?string $activity_workshop): self
    {
        $this->activity_workshop = $activity_workshop;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(?string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getHours(): ?string
    {
        return $this->hours;
    }

    public function setHours(?string $hours): self
    {
        $this->hours = $hours;

        return $this;
    }
}
