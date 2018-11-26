<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

    class CustomerFixtures extends Fixture{
        public function load(ObjectManager $manager)
        {
            $customers = [
                [
                    'name' => 'Tom',
                    'lastname' => 'Bobyman',
                    'email' => 'Tom@tomtom.tom',
                ],
                [
                    'name' => 'John',
                    'lastname' => 'Snow',
                    'email' => 'John@Snow.com',
                ],
                [
                    'name' => 'James',
                    'lastname' => 'Bond',
                    'email' => 'James@Bond.pew',
                ],
            ];
            foreach ($customers as $data) {
                $customer = new Customer();
                $customer->setName($data['name']);
                $customer->setLastname($data['lastname']);
                $customer->setEmail($data['email']);
                $manager->persist($customer);
            }
            $manager->flush();
        }
    }