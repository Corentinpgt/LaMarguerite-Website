<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Access;
use App\Entity\Image;
use App\Repository\ArticleRepository;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $isPublished;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $eventDate;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

	#[ORM\Column(type: 'boolean')]
             private $onFacebook = false;

    #[ORM\Column(type: 'text')]
    private $body;

    #[ORM\ManyToOne(targetEntity: Access::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'article', cascade: ['persist', 'remove'])]
	private $images;

    public function __construct()
    {
        $this->isPublished = false;
        $this->eventDate = new \DateTime();
        $this->images = new ArrayCollection();
    }

    public function equals($object)
    {
        if ($object === null)
            return false;

        if ($this->id == $object->getId())
            return true;
        return false;
    }

    public function display()
    {
        return $this->title;
    }

    public function displayForLog()
    {
        return "[".$this->id."] ".$this->display();
    }

    public function isPublished()
    {
        return $this->isPublished;
    }

    public function isNotPublished()
    {
        return !$this->isPublished();
    }

    public function publish()
    {
        $this->isPublished = true;
    }

    public function unpublish()
    {
        $this->isPublished = false;
    }

	private $files;

    #[ORM\Column(type: 'text', nullable: true)]
    private $facebook_id;
	public function getFiles(){return $this->files;}
	public function setFiles($files){$this->files = $files;}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished( $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getEventDate()
    {
        return $this->eventDate;
    }

    public function setEventDate($eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


	public function isOnFacebook(): ?bool
             {
                 return $this->onFacebook;
             }

    public function publishOnFacebook()
    {
        $this->onFacebook = true;

    }

	public function unpublishOnFacebook()
             {
                 $this->onFacebook = false;
         
             }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthor(): ?Access
    {
        return $this->author;
    }

    public function setAuthor(?Access $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            // Bidirectional Owning Side
            $attachment->setImage($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebook_id;
    }

    public function setFacebookId(?string $facebook_id): self
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

}
