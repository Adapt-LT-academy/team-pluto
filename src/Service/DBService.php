<?php


namespace App\Service;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

class DBService
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return Customer[]|array
     */
    public function getCustomers()
    {
        $customer = new Customer();
        $customer1 = new Customer();

        $customer->setName('Jonas')->setLastname('neturi')->setEmail('toksirtoks');
        $customer1->setName('Jonasass')->setLastname('netasduri')->setEmail('toksirtasdoks');

        return [
            $customer,
            $customer1
        ];

        return $this->em->getRepository(Customer::class)->findAll();

    }


}