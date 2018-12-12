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

    public function isExistingDoc(String $startingDoc)
    {
        $availableInDB = $this->em->getRepository(Ferry::class)->findBy(array('startingDoc' => $startingDoc));
        if(count($availableInDB) > 0)
        {
            return true;
        }
        return false;
    }


    public function isExistingDestination(String $destinationDoc)
    {
        $availableInDB = $this->em->getRepository(Ferry::class)->findBy(array('destinationDoc' => $destinationDoc));
        if(count($availableInDB) > 0)
        {
            return true;
        }
        return false;
    }

  public function getFerry(String $startingDoc, String $destinationDoc)
  {
      $ferry = $this->em->getRepository(Ferry::class)->findBy(array('startingDoc' => $startingDoc, 'destinationDoc' => $destinationDoc));

    return $ferry;

  }

    /**
     * @param String $destination
     * @return null|object
     */
    public function getDestination(String $destination)
    {
        return $this->em->getRepository(Ferry::class)->findOneBy(array('$startingDoc' => $destination ));
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