<?php
//----------------------------------------------------------------------
// src/Controller/PatientController.php
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

use App\Entity\Patient;
use App\Entity\PatientDiet;
use App\Entity\PatientSophro;
use App\Form\PatientType;
use App\Form\PatientDietType;
use App\Form\PatientSophroType;
use App\Form\PatientCompositeType;
use App\Service\LogTools;

class PatientController extends AbstractController
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

        $patients = $this->em->getRepository(Patient::class)->findAll();

        return $this->render('backoffice/patient/dashboard.html.twig', [
            'patients'     =>  $patients,
        ]);
    }

    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $patient = new Patient();

        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {


            $this->em->persist($patient);

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
				$patientId = $patient->getId();

				$patientDiet = new PatientDiet();
				$patientDiet->setIdPatient($patientId);
				$this->em->persist($patientDiet);
				$this->em->flush();

				$patientSophro = new PatientSophro();
				$patientSophro->setIdPatient($patientId);
				$this->em->persist($patientSophro);
				$this->em->flush();

            }
            else
            {
                // Something went wrong
                $request->getSession()->getFlashBag()->add('error', 'event.error');
            }

            return $this->redirectToRoute('patient_dashboard');
        }

        return $this->renderForm('backoffice/patient/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }

    public function edit(Request $request, Patient $patient): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isGranted('ROLE_ADMIN')) {
			$patientDiet = $this->em->getRepository(PatientDiet::class)->findOneByIdPatient($patient->getId());
			$patientSophro = $this->em->getRepository(PatientSophro::class)->findOneByIdPatient($patient->getId());
			$form = $this->createForm(PatientCompositeType::class, [
				'patient' => $patient,
				'patientDiet' => $patientDiet,
				'patientSophro' => $patientSophro,
			]);
		}

		elseif ($this->isGranted('ROLE_SOPHRO')) {
			$patientSophro = $this->em->getRepository(PatientSophro::class)->findOneByIdPatient($patient->getId());
			$form = $this->createForm(PatientCompositeType::class, [
				'patient' => $patient,
				'patientSophro' => $patientSophro,
			]);
		}

		elseif ($this->isGranted('ROLE_DIET')) {
			$patientDiet = $this->em->getRepository(PatientDiet::class)->findOneByIdPatient($patient->getId());
			$form = $this->createForm(PatientCompositeType::class, [
				'patient' => $patient,
				'patientDiet' => $patientDiet,
			]);
		}

		else {
			$form = $this->createForm(PatientType::class, $patient);
		}


		if ($this->isGranted('ROLE_MANAGE')) {$roles="manage";}
		if ($this->isGranted('ROLE_SOPHRO')) {$roles="sophro";}
		if ($this->isGranted('ROLE_DIET')) {$roles="diet";}
		if ($this->isGranted('ROLE_ADMIN')) {$roles="admin";}

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

			$this->em->persist($patient);

			if ($this->isGranted('ROLE_DIET') || $this->isGranted('ROLE_ADMIN')) {
				$this->em->persist($patientDiet);
			}

			// if ($this->isGranted('ROLE_DIET') && $patientDietForm->isSubmitted() && $patientDietForm->isValid()) {
			// 	$this->em->persist($patientDiet);
			// }
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

            return $this->redirectToRoute('patient_dashboard');
        }

		var_dump($roles);

        return $this->renderForm('backoffice/patient/edit.html.twig', [
			'form' => $form,
			'action' => 'edit',
			'role' => $roles,

        ]);
    }

	public function view(Request $request, Patient $patient): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->renderForm('backoffice/patient/view.html.twig', [
            'patient'      =>  $patient,
        ]);
    }

    	
	public function delete(Request $request, Patient $patient): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	
		$this->em->remove($patient);
	
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
	
		return $this->redirectToRoute('patient_dashboard');
	}
}
