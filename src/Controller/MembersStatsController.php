<?php
//----------------------------------------------------------------------
// src/Controller/MembersStatsController.php
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

use App\Entity\MembersAsso;
use App\Repository\MembersAssoRepository;
use App\Entity\MembersIndi;
use App\Repository\MembersIndiRepository;
use App\Service\LogTools;

class MembersStatsController extends AbstractController
{
    public function __construct(Security $security, ManagerRegistry $doctrine, LogTools $logTools, MembersAssoRepository $membersAssoRepository, MembersIndiRepository $membersIndiRepo)
    {
        $this->logTools = $logTools;
        $this->security = $security;
        $this->em = $doctrine->getManager();
        $this->owner = $this->security->getUser();
		$this->membersAssoRepository = $membersAssoRepository;
		$this->membersIndiRepo = $membersIndiRepo;
    }

    public function dashboard(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

		$currentYear = date('Y');
		$Assos = $this->membersAssoRepository->findAll();

		$indirects = [];
		$indirectsAll = 0;
		foreach ($Assos as $asso) {
			$indirects[] = [
				"asso" => $asso,
				"nbr" => $this->membersIndiRepo->findAllIndirects($asso->getId(), $currentYear),
			];
			$indirectsAll+=$this->membersIndiRepo->findAllIndirects($asso->getId(), $currentYear);
		}


		$asso = $this->em->getRepository(MembersAsso::class)->countByYear($currentYear);
		$indi = $this->em->getRepository(MembersIndi::class)->countByYear($currentYear);

		$assoEarning = $this->em->getRepository(MembersAsso::class)->sumPaymentByYear($currentYear);
		if ($assoEarning == null) $assoEarning = 0;
		$indiEarning = $this->em->getRepository(MembersIndi::class)->sumPaymentByYear($currentYear);
		if ($indiEarning == null) $indiEarning = 0;

		$bothNbr = $asso + $indi;
		$bothEarning = $assoEarning + $indiEarning;

        return $this->render('backoffice/members_stats/dashboard.html.twig', [
			'year' => $currentYear,
			'asso_nbr' => $asso,
			'indi_nbr' => $indi,
			'asso_earning' => $assoEarning,
			'indi_earning' => $indiEarning,
			'both_nbr' => $bothNbr,
			'both_earning' => $bothEarning,

			'indirects' => $indirects,
			'indirectsAll' => $indirectsAll,
			'pourcentage' => $indi == 0 ? 0 : round(($indirectsAll / $indi) * 100, 2),
		]);
    }
	
    public function searchYear(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

		$year = $request->query->get('year');
		$Assos = $this->membersAssoRepository->findAll();

		$indirects = [];
		$indirectsAll = 0;
		foreach ($Assos as $asso) {
			$indirects[] = [
				"asso" => $asso,
				"nbr" => $this->membersIndiRepo->findAllIndirects($asso->getId(), $year),
			];
			$indirectsAll+=$this->membersIndiRepo->findAllIndirects($asso->getId(), $year);
		}

		$asso = $this->em->getRepository(MembersAsso::class)->countByYear($year);
		$indi = $this->em->getRepository(MembersIndi::class)->countByYear($year);

		$assoEarning = $this->em->getRepository(MembersAsso::class)->sumPaymentByYear($year);
		if ($assoEarning == null) $assoEarning = 0;
		$indiEarning = $this->em->getRepository(MembersIndi::class)->sumPaymentByYear($year);
		if ($indiEarning == null) $indiEarning = 0;

		$bothNbr = $asso + $indi;
		$bothEarning = $assoEarning + $indiEarning;

        return $this->render('backoffice/members_stats/template_card.html.twig', [
			'year' => $year,
			'asso_nbr' => $asso,
			'indi_nbr' => $indi,
			'asso_earning' => $assoEarning,
			'indi_earning' => $indiEarning,
			'both_nbr' => $bothNbr,
			'both_earning' => $bothEarning,

			'indirects' => $indirects,
			'indirectsAll' => $indirectsAll,
			'pourcentage' => $indi == 0 ? 0 : round(($indirectsAll / $indi) * 100, 2),
		]);
    }
	
}
