<?php

namespace App\Entity;

use App\Entity\Image;
use App\Repository\ContributorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContributorRepository::class)]
class Contributor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $firstname;

    #[ORM\Column(length: 255)]
    private ?string $lastname;

    #[ORM\Column(length: 255)]
    private ?string $job;

    #[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private $profile_pic;

    #[ORM\Column(length: 255)]
    private ?string $mail;

    #[ORM\Column(length: 255)]
    private ?string $phone;

    #[ORM\Column(nullable: true)]
    private ?int $rate_hour = null;

    #[ORM\Column(nullable: true)]
    private ?int $cost_year = null;

    #[ORM\Column(nullable: true)]
    private ?int $income_year = null;

    #[ORM\Column]
    private ?int $nbr_service = 0;

    #[ORM\OneToMany(mappedBy: 'contributor', targetEntity: Workshop::class)]
    private $workshops;

    public function __construct()
    {
        $this->workshops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getProfilePic(): ?Image
    {
        return $this->profile_pic;
    }

    public function setProfilePic(Image $profile_pic): static
    {
        $this->profile_pic = $profile_pic;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRateHour(): ?int
    {
        return $this->rate_hour;
    }

    public function setRateHour(?int $rate_hour): static
    {
        $this->rate_hour = $rate_hour;

        return $this;
    }

    public function getCostYear(): ?int
    {
        return $this->cost_year;
    }

    public function setCostYear(?int $cost_year): static
    {
        $this->cost_year = $cost_year;

        return $this;
    }

    public function getIncomeYear(): ?int
    {
        return $this->income_year;
    }

    public function setIncomeYear(?int $income_year): static
    {
        $this->income_year = $income_year;

        return $this;
    }

    public function getNbrService(): ?int
    {
        return $this->nbr_service;
    }

    public function setNbrService(int $nbr_service): static
    {
        $this->nbr_service = $nbr_service;

        return $this;
    }

    /**
     * @return Collection<int, Workshop>
     */
    public function getWorkshops(): Collection
    {
        return $this->workshops;
    }

    public function addWorkshop(Workshop $workshop): self
    {
        if (!$this->workshops->contains($workshop)) {
            $this->workshops[] = $workshop;
            $workshop->setContributor($this);
        }

        return $this;
    }

    public function removeWorkshop(Workshop $workshop): self
    {
        if ($this->workshops->removeElement($workshop)) {
            // set the owning side to null (unless already changed)
            if ($workshop->getContributor() === $this) {
                $workshop->setContributor(null);
            }
        }

        return $this;
    }
}
