<?php
//----------------------------------------------------------------------
// src/Controller/UserController.php
//----------------------------------------------------------------------
namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Access;
use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Form\DocumentType;
use App\Form\UserEditType;
use App\Form\UserPwdType;
use App\Service\LogTools;

class WorkshopController extends AbstractController
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

        $workshop = $this->em->getRepository(Workshop::class)->findAll();

        return $this->render('backoffice/workshop/dashboard.html.twig', [
            'workshop'     =>  $workshop,
        ]);
    }

    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $workshop = new Workshop();

        $form = $this->createForm(WorkshopType::class, $workshop);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {


            $this->em->persist($workshop);

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

            return $this->redirectToRoute('workshop_dashboard');
        }

        return $this->renderForm('backoffice/workshop/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }

    public function edit(Request $request, Workshop $workshop): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(WorkshopType::class, $workshop);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
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

            return $this->redirectToRoute('workshop_dashboard');
        }

        return $this->renderForm('backoffice/workshop/edit.html.twig', [
            'form'      =>  $form,
            'workshop'      =>  $workshop,
            'action'    =>  'edit',
        ]);
    }

    	
	public function delete(Request $request, Workshop $workshop): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	
		$this->em->remove($workshop);
	
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
	
		return $this->redirectToRoute('workshop_dashboard');
	}


	// Search by Category

	public function searchByCat(Request $request): JsonResponse
	{

		// Get the Id of the Category
	
		$id = $request->query->get('id');

		$id = strip_tags($id);

	
		// Get the workshops that match

		$workshop = $this->em->getRepository(Workshop::class)->findBy(['workshop_category' => $id]);



		// Get all the data to send to the front
		
		$workshopsArray = [];
		foreach ($workshop as $work) {
			$cat = $work->getWorkshopCategory();
			$contributor = $work->getContributor();
			$image = $contributor->getProfilePic();
			$imgToSend = $image->getId() . "." . $image->getExtension();
			$workshopsArray[] = [
				'id' => $work->getId(),
				'name' => $work->getName(),
				'info' => $work->getInfoWorkshop(),
				'activity' => $work->getActivityWorkshop(),
				'place' => $work->getPlace(),
				'day' => $work->getDay(),
				'hours' => $work->getHours(),
				'cat_name' => $cat->getName(),
				'cat_color' => $cat->getColor(),
				'cont_firstname' => $contributor->getFirstname(),
				'cont_lastname' => $contributor->getLastname(),
				'cont_image' => $imgToSend,
				
			];
		}

		// Send them

		return new JsonResponse($workshopsArray, 200);
		
		
	}


	// Search by keywords

	public function searchByContent(Request $request): JsonResponse
	{


		// Get the value of the Searchbar
	
		$content = $request->query->get('value');

		$content = strip_tags($content);

		
		// Create an Array of the words in the searchbar

		$words = explode(" ", $content);

		// Get the workshops that match all the words
		$workshopsCount = [];
		$workshopsData = [];
		foreach ($words as $word) {
			$workshops = $this->em->getRepository(Workshop::class)->findAllByContent($word);

			foreach ($workshops as $work) {

				// Get the category of the workshop
				$cat = $work->getWorkshopCategory();

				// Get the contributor of the workshop
				$contributor = $work->getContributor();
				
				// Get and transform the image into the name of the file
				$image = $contributor->getProfilePic();
				$imgToSend = $image->getId() . "." . $image->getExtension();

				// Get all the data of the Workshops that match
				$workshopData = [
					'id' => $work->getId(),
					'name' => $work->getName(),
					'info' => $work->getInfoWorkshop(),
					'activity' => $work->getActivityWorkshop(),
					'place' => $work->getPlace(),
					'day' => $work->getDay(),
					'hours' => $work->getHours(),
					'cat_name' => $cat->getName(),
					'cat_color' => $cat->getColor(),
					'cont_firstname' => $contributor->getFirstname(),
					'cont_lastname' => $contributor->getLastname(),
					'cont_image' => $imgToSend,
				];

				// Count the occurrences of each workshop (if it already matched, then we increase the count, otherwise we set it as 1 and we put the data in it)
				if (isset($workshopsCount[$work->getId()])) {
					$workshopsCount[$work->getId()]++;
				} else {
					$workshopsCount[$work->getId()] = 1;
					$workshopsData[$work->getId()] = $workshopData;
				}
			}
		}

		// Keep the workshops that matched all the words
		$result = [];
		foreach ($workshopsCount as $id => $count) {
			if ($count == count($words)) {
				$result[] = $workshopsData[$id];
			}
		}

		return new JsonResponse($result, 200);
		
		
	}

	public function uploadPlanning(Request $request)
	{

		// This form is not related to an entity
		$form = $this->createForm(DocumentType::class, null, array(
		));
		
		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid())
			{
				// Get the file
				$uploadFileObject = $form['document']->getData();

				// Process file
				if ($uploadFileObject !== null)
				{


					
					// Create repo path
					$newPath = 'workshop_planning/';

					// Create new filename
					$newFilename = 'planning_ateliers_' . date('YmdHis') . '.pdf';

					// Move the file to the repo
					$uploadFileObject->move($newPath, $newFilename);
					

					$request->getSession()->getFlashBag()->add('notice', 'event.success');				
					
					return $this->redirectToRoute('dashboard');

				
				}
			}
		}
		
		return $this->render('backoffice/workshop/planning.html.twig', array(			
				'form'	=> $form->createView(),	
				'document' =>$form['document']->getData(),
		));
	}
}
