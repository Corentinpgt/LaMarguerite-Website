<?php
//----------------------------------------------------------------------
// src/Controller/MembershipAssoController.php
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
use App\Entity\MembershipAsso;
use App\Form\MembershipAssoType;
use App\Service\LogTools;

class MembershipAssoController extends AbstractController
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

        $membershipAsso = $this->em->getRepository(MembershipAsso::class)->findAll();

        return $this->render('backoffice/membership_asso/dashboard.html.twig', [
            'membership_asso'     =>  $membershipAsso,
        ]);
    }

	public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $membershipAsso = new MembershipAsso();

        $form = $this->createForm(MembershipAssoType::class, $membershipAsso);

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

            return $this->redirectToRoute('membership_asso_dashboard');
        }

        return $this->renderForm('backoffice/membership_asso/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }


	public function view(Request $request, MembershipAsso $membershipAsso): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        return $this->render('backoffice/membership_asso/view.html.twig', [
            'membership_asso'     =>  $membershipAsso,
        ]);
    }


    	
	public function delete(Request $request, MembershipAsso $membershipAsso): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	
		$this->em->remove($membershipAsso);
	
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
	
		return $this->redirectToRoute('membership_asso_dashboard');
	}
}
