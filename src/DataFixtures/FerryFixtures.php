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
                    'starting_doc' => 'Klaipėda',
                    'destination_doc' => 'Ryga',
                    'max_passengers' => '10',
                    'max_vehicles' => '2',
                    'price_per_passenger' => 6000,
                    'price_per_vehicle' => 12000,
                    'date' => '2012-03-06 17:33:07',
                ],
                [
                    'starting_doc' => 'Klaipėda',
                    'destination_doc' => 'Talinas',
                    'max_passengers' => '20',
                    'max_vehicles' => '5',
                    'price_per_passenger' => 4000,
                    'price_per_vehicle' => 10000,
                    'date' => '2012-03-06 17:33:07',
                ],
                [
                    'starting_doc' => 'Klaipėda',
                    'destination_doc' => 'Kylis',
                    'max_passengers' => '20',
                    'max_vehicles' => '5',
                    'price_per_passenger' => 4000,
                    'price_per_vehicle' => 10000,
                    'date' => '2012-03-06 17:33:07',
                ],
            ];
            //Use timestamp
            foreach ($ferries as $data) {
                $ferry = new Ferry();
                $ferry->setStartingDoc($data['starting_doc']);
                $ferry->setDestinationDoc($data['destination_doc']);
                $ferry->setMaxPassengers($data['max_passengers']);
                $ferry->setMaxVehicles($data['max_vehicles']);
                $ferry->setPricePerPassenger($data['price_per_passenger']);
                $ferry->setPricePerVehicle($data['price_per_vehicle']);
                $now = new DateTime();
                $now->format('Y-m-d H:i:s');
                $ferry->setDate($now);
                $manager->persist($ferry);
            }
            $manager->flush();
        }

    }