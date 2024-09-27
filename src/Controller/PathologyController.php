<?php
//----------------------------------------------------------------------
// src/Controller/PathologyController.php
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

use App\Entity\Pathology;
use App\Form\PathologyType;
use App\Service\LogTools;

class PathologyController extends AbstractController
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

        $pathologys = $this->em->getRepository(Pathology::class)->findAll();

        return $this->render('backoffice/pathology/dashboard.html.twig', [
            'pathologys'     =>  $pathologys,
        ]);
    }

    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $pathology = new Pathology();

        $form = $this->createForm(PathologyType::class, $pathology);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {


            $this->em->persist($pathology);

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

            return $this->redirectToRoute('pathology_dashboard');
        }

        return $this->renderForm('backoffice/pathology/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }

    public function edit(Request $request, Pathology $pathology): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(PathologyType::class, $pathology);

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

            return $this->redirectToRoute('pathology_dashboard');
        }

        return $this->renderForm('backoffice/pathology/edit.html.twig', [
            'form'      =>  $form,
            'pathology'      =>  $pathology,
            'action'    =>  'edit',
        ]);
    }

    	
	public function delete(Request $request, Pathology $pathology): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	
		$this->em->remove($pathology);
	
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
	
		return $this->redirectToRoute('pathology_dashboard');
	}
}
