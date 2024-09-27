<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'date', nullable: true)]
    private $birthdate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $gender;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone;

    #[ORM\Column(type: 'string', length: 510, nullable: true)]
    private $email;

    #[ORM\Column(type: 'text', nullable: true)]
    private $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $origin;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $mobility;

    #[ORM\Column(type: 'date')]
    private $joinDate;

    #[ORM\Column(type: 'date', nullable: true)]
    private $leftDate;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $imgLaw;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $job;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $situation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nbrChildren;

    #[ORM\ManyToMany(targetEntity: Pathology::class, inversedBy: 'patients')]
    private $pathology;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $followUp;

    public function __construct()
    {
        $this->pathology = new ArrayCollection();
		$this->joinDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate( $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getMobility(): ?string
    {
        return $this->mobility;
    }

    public function setMobility(?string $mobility): self
    {
        $this->mobility = $mobility;

        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }

    public function setJoinDate(\DateTimeInterface $joinDate): self
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    public function getLeftDate(): ?\DateTimeInterface
    {
        return $this->leftDate;
    }

    public function setLeftDate(?\DateTimeInterface $leftDate): self
    {
        $this->leftDate = $leftDate;

        return $this;
    }

    public function isImgLaw(): ?bool
    {
        return $this->imgLaw;
    }

    public function setImgLaw(?bool $imgLaw): self
    {
        $this->imgLaw = $imgLaw;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getNbrChildren(): ?int
    {
        return $this->nbrChildren;
    }

    public function setNbrChildren(?int $nbrChildren): self
    {
        $this->nbrChildren = $nbrChildren;

        return $this;
    }

    /**
     * @return Collection<int, Pathology>
     */
    public function getPathology(): Collection
    {
        return $this->pathology;
    }

    public function addPathology(Pathology $pathology): self
    {
        if (!$this->pathology->contains($pathology)) {
            $this->pathology[] = $pathology;
        }

        return $this;
    }

    public function removePathology(Pathology $pathology): self
    {
        $this->pathology->removeElement($pathology);

        return $this;
    }

    public function isFollowUp(): ?bool
    {
        return $this->followUp;
    }

    public function setFollowUp(?bool $followUp): self
    {
        $this->followUp = $followUp;

        return $this;
    }
}
