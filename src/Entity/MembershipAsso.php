<?php

namespace App\Entity;

use App\Repository\MembershipAssoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembershipAssoRepository::class)]
class MembershipAsso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $name_asso;

    #[ORM\Column(length: 510)]
    private ?string $address_asso;

    #[ORM\Column(length: 255)]
    private ?string $name_president;

    #[ORM\Column(length: 255)]
    private ?string $mail_president;

    #[ORM\Column(length: 255)]
    private ?string $tel_president;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name_contact;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $position_contact;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_contact;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel_contact;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation;

	public function __construct() {
		$this->date_creation = new \DateTime();
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameAsso(): ?string
    {
        return $this->name_asso;
    }

    public function setNameAsso(string $name_asso): static
    {
        $this->name_asso = $name_asso;

        return $this;
    }

    public function getAddressAsso(): ?string
    {
        return $this->address_asso;
    }

    public function setAddressAsso(string $address_asso): static
    {
        $this->address_asso = $address_asso;

        return $this;
    }

    public function getNamePresident(): ?string
    {
        return $this->name_president;
    }

    public function setNamePresident(string $name_president): static
    {
        $this->name_president = $name_president;

        return $this;
    }

    public function getMailPresident(): ?string
    {
        return $this->mail_president;
    }

    public function setMailPresident(string $mail_president): static
    {
        $this->mail_president = $mail_president;

        return $this;
    }

    public function getTelPresident(): ?string
    {
        return $this->tel_president;
    }

    public function setTelPresident(string $tel_president): static
    {
        $this->tel_president = $tel_president;

        return $this;
    }

    public function getNameContact(): ?string
    {
        return $this->name_contact;
    }

    public function setNameContact(?string $name_contact): static
    {
        $this->name_contact = $name_contact;

        return $this;
    }

    public function getPositionContact(): ?string
    {
        return $this->position_contact;
    }

    public function setPositionContact(?string $position_contact): static
    {
        $this->position_contact = $position_contact;

        return $this;
    }

    public function getMailContact(): ?string
    {
        return $this->mail_contact;
    }

    public function setMailContact(?string $mail_contact): static
    {
        $this->mail_contact = $mail_contact;

        return $this;
    }

    public function getTelContact(): ?string
    {
        return $this->tel_contact;
    }

    public function setTelContact(?string $tel_contact): static
    {
        $this->tel_contact = $tel_contact;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }
}
