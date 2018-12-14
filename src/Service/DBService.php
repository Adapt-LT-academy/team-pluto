<?php


namespace App\Service;

use App\Entity\Customer;
use App\Entity\Ferry;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

class DBService
{
    protected $em;

    /**
     * DBService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getFerries(String $startingDoc, String $destinationDoc)
    {
        $ferry = $this->em->getRepository(Ferry::class)->findBy(array('startingDoc' => $startingDoc, 'destinationDoc' => $destinationDoc));

        return $ferry;
    }

    public function getAllStartingDocs()
    {
        $query = $this->em->createQuery('SELECT f FROM App\Entity\Ferry f GROUP BY f.startingDoc');
        //$query = $this->em->createQuery('SELECT DISTINCT f.startingDoc FROM App\Entity\Ferry f WHERE f.startingDoc = :thisDoc')->setParameter('thisDoc', 'Klaipeda');

        return $query->getResult();

    }

    public function getAllDestinationDocs()
    {
        $query = $this->em->createQuery('SELECT f FROM App\Entity\Ferry f GROUP BY f.destinationDoc');
        return $query->getResult();
    }

    public function getCustomer(string $email){
        $availableInDB = $this->em->getRepository(Customer::class)->findOneBy(array('email' => $email));
        if($availableInDB)
        {
            return $availableInDB;
        }
        return null;
    }

    public function saveReservation(Reservation $reservation) {
      $this->em->persist($reservation);
      $this->em->flush();
    }

}