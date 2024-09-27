<?php
//----------------------------------------------------------------------
// src/Controller/website/WebsiteController.php
//----------------------------------------------------------------------
namespace App\Controller\website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Repository\NotificationRepository;
use App\Repository\PartnerRepository;
use App\Repository\MembersAssoRepository;
use App\Repository\EventRepository;
use App\Repository\ProjectRepository;


use App\Entity\Workshop;
use App\Entity\WorkshopCategory;
use App\Entity\MembershipAsso;
use App\Entity\MembershipIndividual;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\website\MembershipAssoTypeOut;
use App\Form\website\MembershipIndiTypeOut;
use App\Service\LogTools;

class WebsiteController extends AbstractController
{
    public function __construct(Security $security, ManagerRegistry $doctrine, LogTools $logTools, NotificationRepository $notifRepository, ArticleRepository $articleRepository, PartnerRepository $partnerRepository, MembersAssoRepository $membersAssoRepo, EventRepository $eventRepository, ProjectRepository $projectRepository)
    {
        $this->logTools = $logTools;
        $this->security = $security;
        $this->em = $doctrine->getManager();
		$this->notifRepository = $notifRepository;
		$this->articleRepository = $articleRepository;
		$this->partnerRepository = $partnerRepository;
		$this->membersAssoRepo = $membersAssoRepo;
		$this->eventRepository = $eventRepository;
		$this->projectRepository = $projectRepository;
    }

	public function home(): Response
    {  
        $notif = $this->notifRepository->findLast();
		
		$result = null;

		if ($notif!=null) {
			if ($notif->getDateOver() > new \DateTime('now')) {
				$result = $notif;
			}
		}else {
			$result = null;
		}

		return $this->render("website/index.html.twig", [
			'notification' => $result,
			'partners' => $this->partnerRepository->findAll(),
			'events' => $this->eventRepository->findNext(),
		]);



    }

    public function legal(): Response
    {  

        return $this->render("website/legal.html.twig", [
            'partners' => $this->partnerRepository->findAll(),
        ]);


    }

    public function missions(): Response
    {  
        return $this->render("website/missions.html.twig" , [
            'partners' => $this->partnerRepository->findAll(),
            'projects' => $this->projectRepository->findAll(),
        ]);
    }

	public function equipe(): Response
    {  
        return $this->render("website/equipe.html.twig" , [
            'partners' => $this->partnerRepository->findAll(),
        ]);
    }

    public function adherents(): Response
    {  
        return $this->render("website/adherents.html.twig" , [
            'members_asso' => $this->membersAssoRepo->findAll(),
            'partners' => $this->partnerRepository->findAll(),
        ]);
    }
    public function ateliers(): Response
    {  
		// Get the repository
		$dir = $this->getParameter('kernel.project_dir') . '/public/workshop_planning';

		// var_dump($dir);

		// Get all the files
		$files = glob($dir . '/planning_ateliers_*.pdf');

		// 
		if ($files == null) {
			$latestFilename = null;
		}
		else {
			
			// Sort to get the more recent file
			$latestFile = array_reduce($files, function ($a, $b) {
				return filemtime($a) > filemtime($b) ? $a : $b;
			});

			// Get the filename + template
			$latestFilename = "href=workshop_planning/".basename($latestFile)." download";
		}



		$workshopCat = $this->em->getRepository(WorkshopCategory::class)->findAll();
		$workshop = $this->em->getRepository(Workshop::class)->findAll();
        return $this->render("website/workshop/ateliers.html.twig" , [
            'partners' => $this->partnerRepository->findAll(),
			'workshop' => $workshop,
			'workshopCat' => $workshopCat,
			'latestFilename' => $latestFilename,
        ]);
    }
    public function actualites(): Response
    {  
        $article = $this->articleRepository->findAllByLatest();

        return $this->render("website/news/actualites.html.twig", [
            'news' => $article,
            'partners' => $this->partnerRepository->findAll(),
        ]);
    }
    public function adhesions(): Response
    {  
        return $this->render("website/adhesions.html.twig" , [
            'partners' => $this->partnerRepository->findAll(),
        ]);
    }

    public function contact(Request $request): Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
			

            $this->em->persist($contact);

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

            return $this->redirectToRoute('contact');
        }

        return $this->renderForm('website/contact/contact.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
			'partners' => $this->partnerRepository->findAll(),
			'recaptcha_site_key' => $_ENV['RECAPTCHA3_KEY']
        ]);
    }

    public function formAdhesion(): Response
    {  
        return $this->render("website/formulaire.html.twig", [
            'partners' => $this->partnerRepository->findAll(),
        ]);
    }

    public function membershipAsso(Request $request, EntityManagerInterface $em): Response
    {


		$membershipAsso = new MembershipAsso();


		$form = $this->createForm(MembershipAssoTypeOut::class, $membershipAsso);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid())
		{


			$this->em->persist($membershipAsso);

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

			return $this->redirectToRoute('home');
		}

		return $formHtml = $this->renderForm('/website/membership/membershipAsso.html.twig', [
			'form' => $form,
			'partners' => $this->partnerRepository->findAll(),
		]);		
	
    }

	public function membershipIndi(Request $request, EntityManagerInterface $em): Response
    {


		$membershipIndi = new MembershipIndividual();


		$form = $this->createForm(MembershipIndiTypeOut::class, $membershipIndi);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid())
		{


			$this->em->persist($membershipIndi);

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

			return $this->redirectToRoute('home');
		}

		return $formHtml = $this->renderForm('/website/membership/membershipIndi.html.twig', [
			'form' => $form,
			'partners' => $this->partnerRepository->findAll(),
		]);		
	
    }
}


// Front : Page formadhésion avec checkbox -> requete ajax à route membership avec param (asso or indi)
// Back : membership -> si asso -> new MembershipAsso else new MembershipIndividual (foreach : do steps)