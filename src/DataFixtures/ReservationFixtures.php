<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Ferry;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface{

    /**
     * @var ObjectManager $manager
     */
    private $manager;
    /**
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $reservation = new Reservation();
        $reservation->setPassengers(2);
        $reservation->setVehicles(1);
        /**
         * @var Customer $customer
         */
        $customer = $this->getObject(Customer::class, ['name' => 'John']);
        $reservation->setCustomers($customer);

        /**
         * @var Ferry $ferry
         */
        $ferry = $this->getObject(Ferry::class, ['starting_doc' => 'Klaipeda']);
        $reservation->setFerry($ferry);

        $reservation->calculateTotal();

        $manager->persist($reservation);
        $manager->flush();
    }

    /**
     * Get Objects from DB.
     *
     * @param mixed $class
     *   Class to search.
     * @param array $search
     *   Array of search parameters.
     *
     * @return null|object
     */
    private function getObject($class, array $search = []) {
        $object = $this->manager
            ->getRepository($class)
            ->findOneBy($search)
        ;
        return $object;
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            FerryFixtures::class,
            CustomerFixtures::class,
        );
    }

}