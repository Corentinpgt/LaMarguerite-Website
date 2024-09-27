<?php

namespace App\Entity;

use App\Repository\MembersIndiRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\MembersAsso;


#[ORM\Entity(repositoryClass: MembersIndiRepository::class)]
class MembersIndi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

	#[ORM\Column(type: 'date')]
    private $membership_date;

	#[ORM\Column(type: 'integer')]
    private $payment;

    #[ORM\Column(type: 'date')]
    private $payment_date;

    #[ORM\Column(type: 'date')]
    private $birthdate;

    #[ORM\Column(type: 'string', length: 510)]
    private $address;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    private $job;

    #[ORM\Column(type: 'boolean')]
    private $imgLaw;

	#[ORM\ManyToOne(targetEntity: MembersAsso::class, inversedBy: 'membershipIndividuals')]
    private $members_of;


	public function __construct()
    {
        $this->payment = 35;
		$this->payment_date = new \DateTime();
		$this->membership_date = new \DateTime();
    }


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


	public function getPayment(): ?int
    {
        return $this->payment;
    }

    public function setPayment(int $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): self
    {
        $this->payment_date = $payment_date;

        return $this;
    }


    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function isImgLaw(): ?bool
    {
        return $this->imgLaw;
    }

    public function setImgLaw(bool $imgLaw): self
    {
        $this->imgLaw = $imgLaw;

        return $this;
    }

    public function getMembersOf(): ?MembersAsso
    {
        return $this->members_of;
    }

    public function setMembersOf(?MembersAsso $members_of): self
    {
        $this->members_of = $members_of;

        return $this;
    }

    public function getMembershipDate(): ?\DateTimeInterface
    {
        return $this->membership_date;
    }

    public function setMembershipDate(\DateTimeInterface $membership_date): self
    {
        $this->membership_date = $membership_date;

        return $this;
    }
}
