<?php


namespace App\Service;

use App\Entity\Ferry;
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
        $availableInDB = $this->em->getRepository(Ferry::class)->findBy(array('$startingDoc' => $startingDoc));
        if(var_dump(count($availableInDB)) > 0)
        {
            return true;
        }
        return false;
    }

    public function isExistingDestination(String $destinationDoc)
    {
        $availableInDB = $this->em->getRepository(Ferry::class)->findBy(array('$destinationDoc' => $destinationDoc));
        if(var_dump(count($availableInDB)) > 0)
        {
            return true;
        }
        return false;
    }

    /**
     * @param String $destination
     * @return null|object
     */
    public function getDestination(String $destination)
    {
        return $this->em->getRepository(Ferry::class)->findOneBy(array('$startingDoc' => $destination ));
    }


}