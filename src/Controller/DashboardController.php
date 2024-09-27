<?php
//----------------------------------------------------------------------
// src/Controller/DashboardController.php
//----------------------------------------------------------------------
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

use App\Service\LogTools;

class DashboardController extends AbstractController
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
		$this->denyAccessUnlessGranted('ROLE_USER');
        $owner = $this->getUser();

        return $this->render('backoffice/dashboard/dashboard.html.twig', [
        ]);

		// SQL
		// https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/data-retrieval-and-manipulation.html#data-retrieval-and-manipulation

        $id = $this->owner->getId();
        $sql = "SELECT year as year, count(*) as nb
                FROM reading_book
                WHERE owner_id=$id
                GROUP BY year
                ORDER BY year DESC";

        $sql = "SELECT email, id, roles FROM access WHERE email = :email";

        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        $email = 'oanalivia';
        $stmt->bindParam('email', $email);
        $resultSet = $stmt->executeQuery();

        $books = $resultSet->fetchAllNumeric();

        // More info on retriving methods :
        // get_class_methods($resultSet)

        // The fetch API of a prepared statement obviously works only for SELECT queries.
        // If you want to execute a statement that does not yield a result set,
        // like INSERT, UPDATE or DELETE for instance, you might want to call executeStatement()
        // instead of executeQuery().

        // Fetching one single result
        $sql = "SELECT count(*) as nb
                FROM reading_book
                WHERE owner_id=:id";

        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $resultSet = $stmt->executeQuery();

        $nbBooks = $resultSet->fetchOne();

        /*
        fetchNumeric() - Retrieves the next row from the statement or false if there are none. The row is fetched as an array with numeric keys where the columns appear in the same order as they were specified in the executed SELECT query. Moves the pointer forward one row, so that consecutive calls will always return the next row.
        fetchAssociative() - Retrieves the next row from the statement or false if there are none. The row is fetched as an associative array where the keys represent the column names as specified in the executed SELECT query. Moves the pointer forward one row, so that consecutive calls will always return the next row.
        fetchOne() - Retrieves the value of the first column of the next row from the statement or false if there are none. Moves the pointer forward one row, so that consecutive calls will always return the next row.
        fetchAllNumeric() - Retrieves all rows from the statement as arrays with numeric keys.
        fetchAllAssociative() - Retrieves all rows from the statement as associative arrays.
        fetchFirstColumn() - Retrieves the value of the first column of all rows.
        */
    }
}
