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

    //select all reservations where ferry id = givenID
    public function getReservation($id)
    {
        $query = $this->em->createQuery('SELECT r FROM App\Entity\Reservation r WHERE r.ferry = :givenID')->setParameter('givenID', $id);

        return $query->getResult();
    }

    public function getCustomer(string $email)
    {
        $availableInDB = $this->em->getRepository(Customer::class)->findOneBy(array('email' => $email));

        if($availableInDB){
            return $availableInDB;
        }
        return null;
    }

    public function getCustomerByID($id)
    {
        $availableInDB = $this->em->getRepository(Customer::class)->findOneBy(array('id' => $id));

        if($availableInDB){
            return $availableInDB;
        }
        return null;
    }
    public function getFerryByID($id)
    {
        $availableInDB = $this->em->getRepository(Ferry::class)->findOneBy(array('id' => $id));

        if($availableInDB){
            return $availableInDB;
        }
        return null;
    }

    public function saveReservation(Reservation $reservation, $customerID, $ferryID) {
        $reservation->setCustomers($this->getCustomer($customerID));
        $reservation->setFerry($this->getFerryByID($ferryID));
        $this->em->persist($reservation);
        $this->em->flush();
    }
    public function saveCustomerToDB(Customer $customer) {
        $this->em->persist($customer);
        $this->em->flush();
    }


}