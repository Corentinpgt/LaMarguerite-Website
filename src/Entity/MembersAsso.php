<?php

namespace App\Entity;

use App\Entity\Image;
use App\Repository\MembersAssoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembersAssoRepository::class)]
class MembersAsso
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

	#[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private $img;

    #[ORM\Column(type: 'string', length: 510, nullable: true)]
    private $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $president_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $president_email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $president_phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $contact_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $contact_job;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $contact_email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $contact_phone;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\OneToMany(mappedBy: 'member_of', targetEntity: MembershipIndividual::class)]
    private $membershipIndividuals;

    public function __construct()
    {
        $this->membershipIndividuals = new ArrayCollection();
		$this->payment = 40;
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

    public function getMembershipDate(): ?\DateTimeInterface
    {
        return $this->membership_date;
    }

    public function setMembershipDate(\DateTimeInterface $membership_date): self
    {
        $this->membership_date = $membership_date;

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

	public function getImg(): ?Image
                                     {
                                         return $this->img;
                                     }

    public function setImg(Image $img): static
    {
        $this->img = $img;

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

    public function getPresidentName(): ?string
    {
        return $this->president_name;
    }

    public function setPresidentName(?string $president_name): self
    {
        $this->president_name = $president_name;

        return $this;
    }

    public function getPresidentEmail(): ?string
    {
        return $this->president_email;
    }

    public function setPresidentEmail(?string $president_email): self
    {
        $this->president_email = $president_email;

        return $this;
    }

    public function getPresidentPhone(): ?string
    {
        return $this->president_phone;
    }

    public function setPresidentPhone(?string $president_phone): self
    {
        $this->president_phone = $president_phone;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(?string $contact_name): self
    {
        $this->contact_name = $contact_name;

        return $this;
    }

    public function getContactJob(): ?string
    {
        return $this->contact_job;
    }

    public function setContactJob(?string $contact_job): self
    {
        $this->contact_job = $contact_job;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }

    public function setContactEmail(?string $contact_email): self
    {
        $this->contact_email = $contact_email;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function setContactPhone(?string $contact_phone): self
    {
        $this->contact_phone = $contact_phone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, MembershipIndividual>
     */
    public function getMembershipIndividuals(): Collection
    {
        return $this->membershipIndividuals;
    }

    public function addMembershipIndividual(MembershipIndividual $membershipIndividual): self
    {
        if (!$this->membershipIndividuals->contains($membershipIndividual)) {
            $this->membershipIndividuals[] = $membershipIndividual;
            $membershipIndividual->setMemberOf($this);
        }

        return $this;
    }

    public function removeMembershipIndividual(MembershipIndividual $membershipIndividual): self
    {
        if ($this->membershipIndividuals->removeElement($membershipIndividual)) {
            // set the owning side to null (unless already changed)
            if ($membershipIndividual->getMemberOf() === $this) {
                $membershipIndividual->setMemberOf(null);
            }
        }

        return $this;
    }


}
