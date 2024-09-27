<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\Access;
use App\Entity\Article;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Image
{
	const rootUrlForDisplay = 'https://nexus.ploop.eu/assets/images';
	const uploadDirPath = 'assets/images';
	const uploadDirPathAbs = __DIR__.'/../../public/assets/images';

    const allowedMimeTypes = array("image/png", "image/jpeg", "image/jpg");
    const allowedExtensions = array("png", "jpeg", "jpg");

	const maxWidth = 1920;
    const maxHeight = 1080;

    const FORM_ALLOWED_MIME_DISPLAY = "*.png, *.jpeg, *.jpg";
    const FORM_ALLOWED_MIME = array("image/png", "image/jpeg", "image/jpg");
    const FORM_MAX_SIZE = "16M";
    const FORM_MAX_SIZE_MSG = "File size exceeds the limit allowed and cannot be saved.";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $creationDate;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $extension;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $uuid;

	#[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'images')]
	#[ORM\JoinColumn(name: 'article_id')]
	private $article;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->directUpload = 0;
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
        return $this->name;
    }

    private $directUpload;
    public function getDirectUpload()
    {return $this->directUpload;}
    public function setDirectUpload($directUpload)
    {$this->directUpload = $directUpload;}

    // UploadedFile will map to this
	private $file;

	// Temporary save filename
	private $tempFilename;

	public function getClientOriginalName()
	{
		if ($this->file !== null)
			return $this->file->getClientOriginalName();

		return null;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;

		// Check mime type
		$mime = $this->file->getMimeType();
		if (!in_array($mime, self::allowedMimeTypes))
		{
            return;
		}

		// Check to see if we already had a file for this entity
		if (null !== $this->extension)
		{
			// Save file extension in order to delete it later
			$this->tempFilename = $this->extension;

			// Reset extension and name values
			$this->extension = null;
			$this->name = null;
		}
	}

    #[ORM\PrePersist()]
	#[ORM\PreUpdate()]
	public function preUpload()
	{
		if ($this->directUpload)	return;

		// No file => Die hard
		if (null === $this->file)
		{
			return;
		}

		// File name = file id
		// Save extension also
		$this->extension = $this->file->getClientOriginalExtension();

		// Save the original name also, for displaying purposes
		$this->name = $this->file->getClientOriginalName();
	}

    #[ORM\PostPersist()]
    #[ORM\PostUpdate()]
	public function upload()
	{
		if ($this->directUpload)	return;

		// No file => Die hard
		if (null === $this->file)
		{
			return;
		}

		// Existing file => Delete it
		if (null !== $this->tempFilename)
		{
			$oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
			if (file_exists($oldFile))
			{
				unlink($oldFile);
			}
		}

		// Move new file to correct folder
		$this->file->move(
			$this->getUploadRootDir(), 			// Destination folder
			$this->id.'.'.$this->extension   	// New file name : « id.extension »
		);

        $original = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;

		// Resize
		if ($this->hasExifData())
		{
			$exif = @exif_read_data($original);
		}
		else
		{
			$exif = null;
		}

		if ($exif !== null && !empty($exif) && array_key_exists('Orientation', $exif))
			$ort = $exif['Orientation'];
		else
			$ort = null;

		list($width, $height) = getimagesize($original);
		if ($width > self::maxWidth || $height > self::maxHeight)
		{
			$this->smart_resize_image($original, null, self::maxWidth, self::maxHeight, true, $original, false, false, 75, false, $ort);
		}
	}

    #[ORM\PreRemove()]
	public function preRemoveUpload()
	{
		if ($this->directUpload)	return;

		// Save file name, since it depends on the id,
		// and we need to be able to find it after the database entry has been deleted
		$this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
	}

    #[ORM\PostRemove()]
	public function removeUpload()
	{
		if ($this->directUpload)	return;

		// PostRemove : id is no longer available => use temporary filename
		if (file_exists($this->tempFilename))
		{
			// Delete file
			unlink($this->tempFilename);
		}
	}

	public function hasExifData()
	{
		$ext_whitelist = array('jpg', 'jpeg');
		$extension = strtolower($this->extension);

		if (in_array($extension, $ext_whitelist))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getUploadDir()
	{
		// On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /public donc)
		return self::uploadDirPath;
	}

	public function getUploadRootDir()
	{
		// On retourne le chemin absolue vers l'image pour notre code PHP
		$root = self::uploadDirPathAbs."/";
		if (!file_exists($root))
		{
			mkdir($root, 0775, true);
		}
		return $root;
	}

	public function getUrl()
    {
 	   return $this->id.'.'.$this->extension;
    }

    public function getFileName()
    {
 	   return $this->name;
    }

	public function getQualityUrlForDisplay($quality = 0)
	{
        $root = self::uploadDirPath."/";

		// Don't resize if quality is 0
		if ($quality == 0)
		{
			$fileName = $this->id.'.'.$this->extension;
            $path = $root.$fileName;
            return $path;
		}

		// No point in doing anything if the image is already small
		$original = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
		list($width, $height) = getimagesize($original);
		if ($width <= $quality && $height <= $quality)
		{
            $fileName = $this->id.'.'.$this->extension;
            $path = $root.$fileName;
            return $path;
		}

		// Quality is not null
		// Craft potential path
		// 		path/id_quality.extension
		$fileName = $this->id . '_' . $quality . '.' . $this->extension;
		$path = $this->getUploadRootDir().'/'. $fileName;

		// Test if the file exists
		if (file_exists($path) === true)
		{
			$fileName = $this->id . '_' . $quality . '.' . $this->extension;
            $path = $root.$fileName;
            return $path;
		}

		// The file does not exist
		// Create it

		if ($this->hasExifData())
		{
			$exif = @exif_read_data($original);
		}
		else
		{
			$exif = null;
		}

		if ($exif !== null && !empty($exif) && array_key_exists('Orientation', $exif))
			$ort = $exif['Orientation'];
		else
			$ort = null;

		$this->smart_resize_image($original, null, $quality, 0, true, $path, false, false, 75, false, $ort);

		// Rewrite path for web
		$path = $root.$fileName;
		return $path;
	}

	/**
	 * easy image resize function
	 * @param  $file - file name to resize
	 * @param  $string - The image data, as a string
	 * @param  $width - new image width
	 * @param  $height - new image height
	 * @param  $proportional - keep image proportional, default is no
	 * @param  $output - name of the new file (include path if needed)
	 * @param  $delete_original - if true the original image will be deleted
	 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
	 * @param  $quality - enter 1-100 (100 is best quality) default is 100
	 * @param  $grayscale - if true, image will be grayscale (default is false)
	 * @return boolean|resource
	 */
	  public function smart_resize_image($file,
	                              $string             = null,
	                              $width              = 0,
	                              $height             = 0,
	                              $proportional       = false,
	                              $output             = 'file',
	                              $delete_original    = true,
	                              $use_linux_commands = false,
	                              $quality            = 100,
	                              $grayscale          = false,
								  $ort				  = null
	  		 )
		{


			if ( $height <= 0 && $width <= 0 ) return false;
			if ( $file === null && $string === null ) return false;

		# Setting defaults and meta
		$info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
		$image                        = '';
		$final_width                  = 0;
		$final_height                 = 0;
		list($width_old, $height_old) = $info;
		$cropHeight = $cropWidth = 0;

		# Calculating proportionality
		if ($proportional)
		{
			if      ($width  == 0)  $factor = $height/$height_old;
			elseif  ($height == 0)  $factor = $width/$width_old;
				else                    $factor = min( $width / $width_old, $height / $height_old );
			$final_width  = round( $width_old * $factor );
			$final_height = round( $height_old * $factor );
		}
		else
		{
			$final_width = ( $width <= 0 ) ? $width_old : $width;
			$final_height = ( $height <= 0 ) ? $height_old : $height;
			$widthX = $width_old / $width;
			$heightX = $height_old / $height;

			$x = min($widthX, $heightX);
			$cropWidth = ($width_old - $width * $x) / 2;
			$cropHeight = ($height_old - $height * $x) / 2;
		}

		# Loading image to memory according to type
		switch ( $info[2] )
		{
			case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
			default: return false;
		}

		# Making the image grayscale, if needed
		if ($grayscale)
		{
			imagefilter($image, IMG_FILTER_GRAYSCALE);
		}

		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) )
		{
			$transparency = imagecolortransparent($image);
			$palletsize = imagecolorstotal($image);
			if ($transparency >= 0 && $transparency < $palletsize)
			{
				$transparent_color  = imagecolorsforindex($image, $transparency);
				$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			}
			elseif ($info[2] == IMAGETYPE_PNG)
			{
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);

		// Reapply original Orientation
		if (!empty($ort))
		{
	        switch ($ort)
			{
	            case 3:
	                $image_resized = imagerotate($image_resized, 180, 0);
	                break;

	            case 6:
	                $image_resized = imagerotate($image_resized, -90, 0);
	                break;

	            case 8:
	                $image_resized = imagerotate($image_resized, 90, 0);
	                break;
	        }
	    }

		# Taking care of original, if needed
		if ( $delete_original )
		{
			@unlink($file);
		}

		# Preparing a method of providing result
		switch ( strtolower($output) )
		{
			case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
			break;
			case 'file':
				$output = $file;
				break;
			case 'return':
				return $image_resized;
				break;
			default:
			break;
		}

		# Writing image according to type to the output destination and image quality
		switch ( $info[2] )
		{
			case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
			case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
			case IMAGETYPE_PNG:
			$quality = 9 - (int)((0.9*$quality)/10.0);
			imagepng($image_resized, $output, $quality);
			break;
			default: return false;
		}
		return true;
  	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
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

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

	public function getArticle(): ?Article
   	{
   		return $this->article;
   	}

	public function setArticle(?Article $article): self
   	{
   		$this->article = $article;

   		return $this;
   	}


}
