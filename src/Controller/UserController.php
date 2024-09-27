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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\Access;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Form\UserPwdType;
use App\Service\LogTools;


class UserController extends AbstractController
{
    public function __construct(Security $security, ManagerRegistry $doctrine, LogTools $logTools, UserPasswordHasherInterface $passwordHasher)
    {
        $this->logTools = $logTools;
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
        $this->em = $doctrine->getManager();
        $this->owner = $this->security->getUser();
    }

	
    public function dashboard(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $users = $this->em->getRepository(Access::class)->findAll();

        return $this->render('backoffice/user/dashboard.html.twig', [
            'users'     =>  $users,
        ]);
    }

    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = new Access();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Encode the password
		    $encodedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
		    $user->setPassword($encodedPassword);

            // TODO : Set the Activation option later
            $user->setIsVerified(1);

            $this->em->persist($user);

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

            return $this->redirectToRoute('user_dashboard');
        }

        return $this->renderForm('backoffice/user/add.html.twig', [
            'form'      =>  $form,
            'action'    =>  'add',
        ]);
    }

    public function edit(Request $request, Access $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(UserEditType::class, $user);

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

            return $this->redirectToRoute('user_dashboard');
        }

        return $this->renderForm('backoffice/user/edit.html.twig', [
            'form'      =>  $form,
            'user'      =>  $user,
            'action'    =>  'edit',
        ]);
    }

    public function editPassword(Request $request, Access $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(UserPwdType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Encode the password
            $encodedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

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

            return $this->redirectToRoute('user_dashboard');
        }

        return $this->renderForm('backoffice/user/edit.html.twig', [
            'form'      =>  $form,
            'user'      =>  $user,
            'action'    =>  'edit_password',
        ]);
    }

    public function delete(Request $request, Access $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->em->remove($user);

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

        return $this->redirectToRoute('user_dashboard');
    }
}
