<?php
//----------------------------------------------------------------------
// src/Controller/ImageController.php
//----------------------------------------------------------------------
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Image;
use App\Entity\News;
use App\Entity\Article;
use App\Service\LogTools;

class ImageController extends AbstractController
{
    public function __construct(Security $security, ManagerRegistry $doctrine, LogTools $logTools)
    {
        $this->logTools = $logTools;
        $this->security = $security;
        $this->em = $doctrine->getManager();
        $this->owner = $this->security->getUser();
    }

	public function addDropzone(Request $request): JsonResponse
	{
		if (!$request->isXmlHttpRequest())
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'incorrect.http.request'),
			400);
		}

		if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'access.denied'),
			403);
		}

		if (!isset($request->request))
		{
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'request.not.set'),
            400);
        }

        $media = $request->files->get('file');
        $uuid = $request->request->get('uuid');

		if ($media === null || empty($media))
		{
            return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'error.get.media'),
			415);
        }

		if ($uuid === null || empty($uuid))
		{
            return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'error.get.data'),
			400);
        }

		// Check extension and mime type
		$allowedMimeTypes = Image::allowedMimeTypes;
		$allowedExtensions = Image::allowedExtensions;

		$extension = strtolower($media->getClientOriginalExtension());
		$mime = strtolower($media->getMimeType());

		$mimeFound = false;
		$extensionFound = false;
		foreach ($allowedMimeTypes as $allowedMimeType)
		{
			if ($mime == $allowedMimeType)
			{
				$mimeFound = true;
                break;
			}
		}
		foreach ($allowedExtensions as $allowedExtension)
		{
			if ($extension == $allowedExtension)
			{
				$extensionFound = true;
                break;
			}
		}
		if (!$mimeFound || !$extensionFound)
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'unsupported.media.type'),
			415);
		}

		// Create a new Image object
		$dataFile = new Image();
		$dataFile->setFile($media);
		$dataFile->setName($media->getClientOriginalName());
		$dataFile->setUuid($uuid);

		$this->em->persist($dataFile);

        // Do we have an Article ?
        $article_id = $request->request->get('article_id');
        if (!empty($article_id))
        {
            $article = $this->em->getRepository(Article::class)->findOneById($article_id);
            if ($article !== null)
            {
                $dataFile->setArticle($article);
            }
        }

		// Do we have an News ?
        $news_id = $request->request->get('new_id');
        if (!empty($news_id))
        {
            $news = $this->em->getRepository(News::class)->findOneById($news_id);
            if ($news !== null)
            {
                $dataFile->setNews($news);
            }
        }

		try
		{
			$this->em->flush();
		}
		catch (\Exception $e)
		{
			$this->logTools->errorlog($e->getMessage());

			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'db.error '.$e->getMessage()),
			400);
		}
		return new JsonResponse(array(
			'status' 	=> 'Done',
			'message' 	=> 'Done',
			'id'		=>	$dataFile->getId(),
			'url'		=>	$dataFile->getQualityUrlForDisplay(0),
		),
		200);
	}

    public function removeDropzone(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest())
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'incorrect.http.request'),
			400);
		}

		if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'access.denied'),
			403);
		}

        if (!isset($request->request))
		{
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'request.not.set'),
            400);
        }

        // Get the code of the file to remove
        $uuid = $request->request->get('uuid');

        if ($uuid === null || empty($uuid))
		{
            return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'error.get.data'),
			400);
        }

        // Does the file exist ?
        $media = $this->em->getRepository(Image::class)->findOneByUuid($uuid);

        if ($media !== null)
        {
            // Remove it
            $this->em->remove($media);

            // Try to save it to the database
            try
            {
                $this->em->flush();
            }
            catch (\Exception $e)
            {
                // Something went bad
                $this->logTools->errorlog($e->getMessage());

                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'db.error'),
                400);
            }
        }

        return new JsonResponse(array(
            'status' => 'Done',
            'message' => 'Done'),
        200);
    }

	public function addSummernote(Request $request): JsonResponse
	{
		if (!$request->isXmlHttpRequest())
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'incorrect.http.request'),
			400);
		}

		// Authentication.Test
		if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			return new JsonResponse(array(
				'status' => 'Error',
				'message' => 'access.denied'),
			403);
		}

		if (isset($request->request))
		{
			$media = $request->files->get('image');

			if ($media === null || empty($media))
				return new JsonResponse(array(
					'status' => 'Error',
					'message' => 'unsupported.media.type'),
				415);

			// Check extension and mime type
			$allowedMimeTypes = Image::allowedMimeTypes;
			$allowedExtensions = Image::allowedExtensions;

			$extension = strtolower($media->getClientOriginalExtension());
			$mime = strtolower($media->getMimeType());

			$mimeFound = false;
			$extensionFound = false;
			foreach ($allowedMimeTypes as $allowedMimeType)
			{
				if ($mime == $allowedMimeType)
				{
					$mimeFound = true;
                    break;
				}
			}
			foreach ($allowedExtensions as $allowedExtension)
			{
				if ($extension == $allowedExtension)
				{
					$extensionFound = true;
                    break;
				}
			}
			if (!$mimeFound || !$extensionFound)
			{
				return new JsonResponse(array(
					'status' => 'Error',
					'message' => 'unsupported.media.type'),
				415);
			}

			// Create a new Image object
			$dataFile = new Image();
			$dataFile->setFile($media);
			$dataFile->setName($media->getClientOriginalName());

			$this->em->persist($dataFile);

			try
			{
				$this->em->flush();
			}
			catch (\Exception $e)
			{
				$this->logTools->errorlog($e->getMessage());

				return new JsonResponse(array(
					'status' => 'Error',
					'message' => 'db.error '.$e->getMessage()),
				400);
			}
			return new JsonResponse(array(
				'status' 	=> 'Done',
				'message' 	=> 'Done',
				'id'		=>	$dataFile->getId(),
				'url'		=>	$dataFile->getQualityUrlForDisplay(0),
			),
			200);
		}

		return new JsonResponse(array(
			'status' => 'Error',
			'message' => 'this.code.should.not.be.reached'),
		400);
	}

	public function showImage(Request $request, Image $image): Response
    {

        // Get the image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/assets/images/' . $image->getId() . "." . $image->getExtension();

        // If image not exist
        if (!file_exists($imagePath)) {
            throw $this->createNotFoundException('L\'image demandée n\'existe pas.');
        }

        // Return image as http response
        $response = new Response(file_get_contents($imagePath));
        $response->headers->set('Content-Type', 'image/jpeg'); // Remplacez par le type MIME approprié si nécessaire

        return $response;
    }
}
