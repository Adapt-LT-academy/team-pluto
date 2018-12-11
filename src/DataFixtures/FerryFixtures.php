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
                ],
                [
                    'startingDoc' => 'Klaipėda',
                    'destinationDoc' => 'Talinas',
                    'maxPassengers' => '20',
                    'maxVehicles' => '5',
                    'pricePerPassenger' => 4000,
                    'pricePerVehicle' => 10000,
                ],
                [
                    'startingDoc' => 'Klaipėda',
                    'destinationDoc' => 'Kylis',
                    'maxPassengers' => '20',
                    'maxVehicles' => '5',
                    'pricePerPassenger' => 4000,
                    'pricePerVehicle' => 10000,
                ],
            ];
            //Use timestamp

          $dates = [
            ['date' => new DateTime('next Monday 9PM')],
            ['date' => new DateTime('next Monday 1AM')],
            ['date' => new DateTime('next Monday 7AM')],
            ['date' => new DateTime('next Thursday 9PM')],
            ['date' => new DateTime('next Thursday 1AM')],
            ['date' => new DateTime('next Thursday 7AM')],
            ['date' => new DateTime('next Saturday 9PM')],
            ['date' => new DateTime('next Saturday 1AM')],
            ['date' => new DateTime('next Saturday 7AM')],

          ];

            foreach ($ferries as $data) {
              foreach ($dates as $date) {
                $ferry = new Ferry();
                $ferry->setStartingDoc($data['startingDoc']);
                $ferry->setDestinationDoc($data['destinationDoc']);
                $ferry->setMaxPassengers($data['maxPassengers']);
                $ferry->setMaxVehicles($data['maxVehicles']);
                $ferry->setPricePerPassenger($data['pricePerPassenger']);
                $ferry->setPricePerVehicle($data['pricePerVehicle']);
                $ferry->setDate($date['date']);
                $manager->persist($ferry);
              }
            }
            $manager->flush();
        }

    }
