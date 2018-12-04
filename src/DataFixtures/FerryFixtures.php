<?php

namespace App\DataFixtures;

use App\Entity\Ferry;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FerryFixtures extends Fixture{
        public function load(ObjectManager $manager)
        {
            $ferries = [
                [
                    'startingDoc' => 'Klaipėda',
                    'destinationDoc' => 'Ryga',
                    'maxPassengers' => '10',
                    'maxVehicles' => '2',
                    'pricePerPassenger' => 6000,
                    'pricePerVehicle' => 12000,
                    'date' => '2012-03-06 17:33:07',
                ],
                [
                    'startingDoc' => 'Klaipėda',
                    'destinationDoc' => 'Talinas',
                    'maxPassengers' => '20',
                    'maxVehicles' => '5',
                    'pricePerPassenger' => 4000,
                    'pricePerVehicle' => 10000,
                    'date' => '2012-03-06 17:33:07',
                ],
                [
                    'startingDoc' => 'Klaipėda',
                    'destinationDoc' => 'Kylis',
                    'maxPassengers' => '20',
                    'maxVehicles' => '5',
                    'pricePerPassenger' => 4000,
                    'pricePerVehicle' => 10000,
                    'date' => '2012-03-06 17:33:07',
                ],
            ];
            //Use timestamp
            foreach ($ferries as $data) {
                $ferry = new Ferry();
                $ferry->setStartingDoc($data['startingDoc']);
                $ferry->setDestinationDoc($data['destinationDoc']);
                $ferry->setMaxPassengers($data['maxPassengers']);
                $ferry->setMaxVehicles($data['maxVehicles']);
                $ferry->setPricePerPassenger($data['pricePerPassenger']);
                $ferry->setPricePerVehicle($data['pricePerVehicle']);
                $now = new DateTime();
                $now->format('Y-m-d H:i:s');
                $ferry->setDate($now);
                $manager->persist($ferry);
            }
            $manager->flush();
        }

    }