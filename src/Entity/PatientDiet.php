<?php

namespace App\Entity;

use App\Repository\PatientDietRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientDietRepository::class)]
class PatientDiet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $workHours;

    #[ORM\Column(type: 'integer')]
    private $idPatient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkHours(): ?string
    {
        return $this->workHours;
    }

    public function setWorkHours(?string $workHours): self
    {
        $this->workHours = $workHours;

        return $this;
    }

    public function getIdPatient(): ?int
    {
        return $this->idPatient;
    }

    public function setIdPatient(int $idPatient): self
    {
        $this->idPatient = $idPatient;

        return $this;
    }
}
