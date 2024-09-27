<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Article;

class FacebookController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient, ManagerRegistry $doctrine)
    {
        $this->httpClient = $httpClient;
		$this->em = $doctrine->getManager();
    }


    public function publish(Request $request): Response
    {

		// Get id & token
        $pageId = $_ENV['FACEBOOK_PAGE_ID'];
        $accessToken = $_ENV['FACEBOOK_ACCESS_TOKEN'];


		// Get the article
		$article_id = $request->request->get('article_id');
		$article = $this->em->getRepository(Article::class)->findOneById($article_id);

		$message = $article->getTitle() . "\n\n" . $article->getBody();

		// Create the url for all the images of the articles
		$imageUrls = [];
		foreach ($article->getImages() as $image) {
			
			$imageUrls[] = 'https://nexus.ploop.eu/api/image/' . $image->getId();
		}



		// Post the image on facebook and get their ID
        $url = "https://graph.facebook.com/{$pageId}/photos";

        $imageIds = [];
        foreach ($imageUrls as $imageUrl) {
            $imageResponse = $this->httpClient->request('POST', $url, [
                'body' => [
                    'url' => $imageUrl,
                    'published' => 'false',
                    'access_token' => $accessToken,
                ],
            ]);

            if ($imageResponse->getStatusCode() !== 200) {
                return new Response('Erreur lors de la publication de l\'image : ' . $imageResponse->getContent(false), Response::HTTP_BAD_REQUEST);
            }

			// Keep all their ID
            $imageData = $imageResponse->toArray();
            if (isset($imageData['id'])) {
                $imageIds[] = $imageData['id'];
            } else {
                return new Response('Erreur : ' . $imageData['error']['message'], Response::HTTP_BAD_REQUEST);
            }
        }


		// Create an array of the images ID
        $attachedMedia = array_map(fn($id) => ['media_fbid' => $id], $imageIds);


		// Create the request to post the article on Facebook
        $postUrl = "https://graph.facebook.com/{$pageId}/feed";
        $postData = [
            'message' => $message,
            'attached_media' => json_encode($attachedMedia),
            'access_token' => $accessToken,
        ];

		// Do the request
        $postResponse = $this->httpClient->request('POST', $postUrl, [
            'body' => $postData,
        ]);

        if ($postResponse->getStatusCode() !== 200) {
            return new JsonResponse('Erreur lors de la publication du post : ' . $postResponse->getContent(false), Response::HTTP_BAD_REQUEST);
        }

        $postResponseData = $postResponse->toArray();
        if (isset($postResponseData['id'])) {
			$article->setFacebookId($postResponseData['id']);
			// Change the status
			$article->publishOnFacebook();
			$this->em->flush();
            return new JsonResponse('Le message a été publié avec succès sur Facebook. ID du post : ' . $postResponseData['id'], 200);
        } else {
            return new JsonResponse('Erreur : ' . $postResponseData['error']['message'], Response::HTTP_BAD_REQUEST);
        }
    }


	public function unpublish(Request $request): Response
    {

		// Get id & token
        $pageId = $_ENV['FACEBOOK_PAGE_ID'];
        $accessToken = $_ENV['FACEBOOK_ACCESS_TOKEN'];


		// Get the article
		$article_id = $request->request->get('article_id');
		$article = $this->em->getRepository(Article::class)->findOneById($article_id);

		$postId = $article->getFacebookId();


		// Create the request to post the article on Facebook
        $postUrl = "https://graph.facebook.com/v20.0/{$postId}?access_token={$accessToken}";

		// Do the request
        $postResponse = $this->httpClient->request('DELETE', $postUrl);

        if ($postResponse->getStatusCode() !== 200) {
            return new JsonResponse('Erreur lors de la suppression du post : ' . $postResponse->getContent(false), Response::HTTP_BAD_REQUEST);
        }

		$postResponseData = $postResponse->toArray();
        if ($postResponseData['success']) {
			$article->setFacebookId("");
			// Change the status
			$article->unpublishOnFacebook();
			$this->em->flush();
			return new JsonResponse('Le post a été supprimé avec succès sur Facebook.', 200);
		} else {
			return new JsonResponse('Erreur : ' . $postResponseData['error']['message'], Response::HTTP_BAD_REQUEST);
		}
        
    }
}
