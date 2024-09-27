<?php
//----------------------------------------------------------------------
// src/Controller/MembershipIndiController.php
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
use App\Entity\MembershipIndividual;
use App\Form\MembershipIndiType;
use App\Service\LogTools;

class MembershipIndiController extends AbstractController
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

        $membershipIndi = $this->em->getRepository(MembershipIndividual::class)->findAll();

        return $this->render('backoffice/membership_indi/dashboard.html.twig', [
            'membership_indi'     =>  $membershipIndi,
        ]);
    }


	public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $membershipIndi = new MembershipIndividual();

        $form = $this->createForm(MembershipIndiType::class, $membershipIndi);

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

            return $this->redirectToRoute('membership_indi_dashboard');
        }

        return $this->renderForm('backoffice/membership_indi/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }


	public function view(Request $request, MembershipIndividual $membershipIndi): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        return $this->render('backoffice/membership_indi/view.html.twig', [
            'membership_indi'     =>  $membershipIndi,
        ]);
    }


    	
	public function delete(Request $request, MembershipIndividual $membershipIndi): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	
		$this->em->remove($membershipIndi);
	
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
	
		return $this->redirectToRoute('membership_indi_dashboard');
	}
}
