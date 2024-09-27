<?php
//----------------------------------------------------------------------
// src/Controller/ArticleController.php
//----------------------------------------------------------------------
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Access;
use App\Entity\Image;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\LogTools;

class ArticleController extends AbstractController
{
    public function __construct(Security $security, ManagerRegistry $doctrine, LogTools $logTools)
    {
        $this->logTools = $logTools;
        $this->security = $security;
        $this->em = $doctrine->getManager();
        $this->owner = $this->security->getUser();
    }

	public function dashboard(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $article = $this->em->getRepository(Article::class)->findAll();

        return $this->render('backoffice/article/dashboard.html.twig', [
            'articles'     =>  $article,
        ]);
    }

    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $article = new Article();
        $article->setAuthor($this->owner);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Handle Dropzone images
            if (!empty($article->getFiles()))
            {
                $files = json_decode($article->getFiles(), true);
                if (!empty($files))
                {
                    foreach ($files as $uuid)
                    {
                        $image = $this->em->getRepository(Image::class)->findOneByUuid($uuid);
                        if ($image !== null)
                        {
                            $image->setArticle($article);
                        }
                    }
                }
            }

            $this->em->persist($article);

            $okey = true;
            try {
				$this->em->flush();
			}
			catch (\Exception $e)
			{
                $this->logTools->errorlog($e->getMessage());
                $okey = false;
            }

            // Inform user of process and redirect
            if ($okey)
            {
                // All went well
                $request->getSession()->getFlashBag()->add('notice', 'event.success');
            }
            else
            {
                // Something went wrong
                $request->getSession()->getFlashBag()->add('error', 'event.error');
            }

            return $this->redirectToRoute('article_dashboard');
        }

        return $this->renderForm('backoffice/article/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }

    public function preview(Request $request, Article $article): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->renderForm('backoffice/article/preview.html.twig', [
            'article'   =>  $article,
        ]);
    }

    public function edit(Request $request, Article $article): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ArticleType::class, $article, array(
            'action'    =>  'edit',
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Handle Dropzone images
            // No need to do this here,
            // since the article_id was present in the form,
            // the images have been attached to the Article during upload
            $okey = true;
            try {
				$this->em->flush();
			}
			catch (\Exception $e)
			{
                $this->logTools->errorlog($e->getMessage());
                $okey = false;
            }

            // Inform user of process and redirect
            if ($okey)
            {
                // All went well
                $request->getSession()->getFlashBag()->add('notice', 'event.success');
            }
            else
            {
                // Something went wrong
                $request->getSession()->getFlashBag()->add('error', 'event.error');
            }

			$article->unpublishOnFacebook();
			$this->em->flush();

            return $this->redirectToRoute('article_dashboard');
        }

        return $this->renderForm('backoffice/article/edit.html.twig', [
            'form'      =>  $form,
            'article'   =>  $article,
            'action'    =>  'edit',
        ]);
    }

    public function delete(Request $request, Article $article): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->em->remove($article);

        $okey = true;
        try {
            $this->em->flush();
        }
        catch (\Exception $e)
        {
            $this->logTools->errorlog($e->getMessage());
            $okey = false;
        }

        // Inform user of process and redirect
        if ($okey)
        {
            // All went well
            $request->getSession()->getFlashBag()->add('notice', 'event.success');
        }
        else
        {
            // Something went wrong
            $request->getSession()->getFlashBag()->add('error', 'event.error');
        }

        return $this->redirectToRoute('article_dashboard');
    }

    public function publish(Request $request)
    {
    	if (!$request->isXmlHttpRequest())
            return new JsonResponse(array('status' => 'Error'),400);

    	if (!isset($request->request))
    		return new JsonResponse(array('status' => 'Error'),400);

        // Check Permissions
    	if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    		return new JsonResponse(array('status' => 'Error'),403);

    	// Get data
    	$article_id = intval($request->request->get('article_id'));
    	$article = $this->em->getRepository(Article::class)->findOneById($article_id);

    	// If null, die hard
    	if ($article === null)
    		return new JsonResponse(array('status' => 'Error', 'msg' => 'object.not.found'),400);

        if ($article->isPublished())
        {
            // Nothing to do here
            return new JsonResponse(array('status' => 'Done'),200);
        }

        $article->publish();

        try {
            $this->em->flush();
        }
        catch (\Exception $e)
        {
            $this->logTools->errorlog($e->getMessage());
            return new JsonResponse(array('status' => 'Error', 'msg' => 'db.error'),400);
        }

    	return new JsonResponse(array('status' => 'Done'),200);
    }

    public function unpublish(Request $request)
    {
    	if (!$request->isXmlHttpRequest())
            return new JsonResponse(array('status' => 'Error'),400);

    	if (!isset($request->request))
    		return new JsonResponse(array('status' => 'Error'),400);

        // Check Permissions
    	if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    		return new JsonResponse(array('status' => 'Error'),403);

    	// Get data
    	$article_id = intval($request->request->get('article_id'));
    	$article = $this->em->getRepository(Article::class)->findOneById($article_id);

    	// If null, die hard
    	if ($article === null)
    		return new JsonResponse(array('status' => 'Error', 'msg' => 'object.not.found'),400);

        if ($article->isNotPublished())
        {
            // Nothing to do here
            return new JsonResponse(array('status' => 'Done'),200);
        }

        $article->unpublish();

        try {
            $this->em->flush();
        }
        catch (\Exception $e)
        {
            $this->logTools->errorlog($e->getMessage());
            return new JsonResponse(array('status' => 'Error', 'msg' => 'db.error'),400);
        }

    	return new JsonResponse(array('status' => 'Done'),200);
    }


	public function searchByContent(Request $request): JsonResponse
	{
	
		// Get the value
		$content = $request->query->get('value');

		$content = strip_tags($content);
		$words = explode(" ", $content);

		$articlesCount = [];
		$articlesData = [];

		// For each words, we search the articles that contain it
		foreach ($words as $word) {
			
			$articles = $this->em->getRepository(Article::class)->findAllByContent($word);

			// Get the data of all the articles that matched the word

			foreach ($articles as $artcl) {

				$images = $artcl->getImages();
				$imagesArray = [];
				foreach ($images as $image) {
					$imagesArray[] = [
						'image' => $image->getId() . "." . $image->getExtension(),
					];
				}

				$articleData = [
					'title' => $artcl->getTitle(),
					'body' => $artcl->getBody(),
					'date' => $artcl->getEventDate(),
					'image' => $imagesArray,
				];

				// Count the occurrences of each article (if it already matched, then we increase the count, otherwise we set it as 1 and we put the data in it)
				if (isset($articlesCount[$artcl->getId()])) {
					$articlesCount[$artcl->getId()]++;
				} else {
					$articlesCount[$artcl->getId()] = 1;
					$articlesData[$artcl->getId()] = $articleData;
				}
			}
		}


		// Keep the articles that matched all the words
		$result = [];
		foreach ($articlesCount as $id => $count) {
			if ($count == count($words)) {
				$result[] = $articlesData[$id];
			}
		}

		return new JsonResponse($result, 200);
		
		
	}
}
