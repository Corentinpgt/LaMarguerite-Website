<?php
//----------------------------------------------------------------------
// src/Controller/MembersIndiController.php
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
use App\Entity\MembersIndi;
use App\Form\MembersIndiType;
use App\Service\LogTools;

class MembersIndiController extends AbstractController
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

        $membersIndi = $this->em->getRepository(MembersIndi::class)->findAll();

        return $this->render('backoffice/members_indi/dashboard.html.twig', [
            'members_indi'     =>  $membersIndi,
        ]);
    }

	public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $membersIndi = new MembersIndi();

        $form = $this->createForm(MembersIndiType::class, $membersIndi);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {


            $this->em->persist($membersIndi);

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

            return $this->redirectToRoute('members_indi_dashboard');
        }

        return $this->renderForm('backoffice/members_indi/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }


	public function edit(Request $request, MembersIndi $MembersIndi): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(MembersIndiType::class, $MembersIndi);

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

            return $this->redirectToRoute('members_indi_dashboard');
        }

        return $this->renderForm('backoffice/members_indi/edit.html.twig', [
            'form'      =>  $form,
            'members_indi'      =>  $MembersIndi,
            'action'    =>  'edit',
        ]);
    }


	public function view(Request $request, MembersIndi $membersIndi): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        return $this->render('backoffice/members_indi/view.html.twig', [
            'members_indi'     =>  $membersIndi,
        ]);
    }


    	
	public function delete(Request $request, MembersIndi $membersIndi): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	
		$this->em->remove($membersIndi);
	
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
	
		return $this->redirectToRoute('members_indi_dashboard');
	}
}
