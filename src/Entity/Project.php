<?php

namespace App\Entity;

use App\Entity\Image;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

	#[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
             private $img;

    #[ORM\Column(type: 'text', nullable: true)]
    private $link;

    #[ORM\Column(type: 'string', length: 510, nullable: true)]
    private $link_label;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLinkLabel(): ?string
    {
        return $this->link_label;
    }

    public function setLinkLabel(?string $link_label): self
    {
        $this->link_label = $link_label;

        return $this;
    }
}
