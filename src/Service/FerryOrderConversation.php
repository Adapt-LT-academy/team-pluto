<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Ferry;
use App\Entity\Reservation;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Traits\ContainerAwareConversationTrait;

class FerryOrderConversation extends Conversation
{
    use ContainerAwareConversationTrait;

    /**
     * @var Reservation
     */
    protected $reservation;

    /**
     * @var Ferry
     */
    protected $ferries;

    /**
     * @var Ferry
     */
    protected $ferry;

    /**
     * @var Customer
     */
    protected $customer;

    protected $startingDoc;

    protected $destinationDoc;






    protected $firstname;

    protected $lastname;

    protected $email;

    protected $passengers;

    protected $vehicle;

    public function run()
    {
      $this->reservation = new Reservation();
      $this->startingQuestion();
    }

    public function startingQuestion()
    {
        $question = Question::create('Would you like to make a reservation?')
            ->fallback('Unable to create a reservation')
            ->callbackId('create_reservation')
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'yes'){
                    $this->askStartingDoc();
                }
            }
        });
    }

    public function askStartingDoc()
    {
        //instead lets have buttons with availabe FROM places

        $question = Question::create('From what doc would you like to book a ferry?');

        $this->ask($question, function (Answer $answer) {
            $exists = $this->getContainer()->get(DBService::class)->isExistingDoc($answer->getText());
            if ($exists) {
                $this->startingDoc = $answer->getText();
                $this->askDestinationDoc();
            } else {
                $this->say('Sorry no ferries from selected location exists. Please try again.');
                $this->askStartingDoc();
            }
        });
    }


    public function askDestinationDoc()
    {
        //instead lets have buttons with availabe FROM places

        $question = Question::create('What is your destination?');

        $this->ask($question, function (Answer $answer) {
            $exists = $this->getContainer()->get(DBService::class)->isExistingDestination($answer->getText());
            if ($exists) {
                $this->destinationDoc = $answer->getText();
                $this->getFerries();
            } else {
                $this->say('Sorry no ferries to selected location exists. Please try again.');
                $this->askDestinationDoc();
            }
        });
    }

    public function getFerries()
    {
        $this->ferries = $this->getContainer()->get(DBService::class)->getFerry($this->startingDoc, $this->destinationDoc);

        $this->askDate();
    }


    public function askDate()
    {
        $buttons = [];

       foreach ($this->ferries as $key=>$ferry) {
            $buttons[] = Button::create($ferry->getDate()->format('M d H:i'))->value($key);
        }

        $question = Question::create('Here are available dates. Chose one:')
            ->callbackId('select_date')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->ferry = $this->ferries[$answer->getText()];
                $this->say('To finish reservation we will need your details.');
                $this->askEmail();
            }
        });
    }

    /* Everything regarding Customer*/

    public function askEmail()
    {
        $this->ask('Please input your email.', function (Answer $answer) {
            if(filter_var($answer->getText(), FILTER_VALIDATE_EMAIL))
            {
                $this->email = $answer->getText();
                $customerExists = $this->getContainer()->get(DBService::class)->getCustomer($this->email);
                if($customerExists != null)
                {
                    $this->customer = $customerExists;
                    $this->say('Your are not first time user.');
                    $this->askPassenger();
                }
                else
                    {
                        $this->say('You are first time user. Please fill in your details.');
                        $this->askFirstname();
                    }
            }
            else
                {
                    $this->say('Email was typed incorrectly, please try again.');
                    $this->askEmail();
                }
        });
    }

    public function askFirstname()
    {
        $this->ask('What is your name?', function(Answer $answer) {
            $this->firstname = $answer->getText();

            $this->askLastname();
        });
    }

    public function askLastname()
    {
        $this->ask('What is your surname?', function(Answer $answer) {
            $this->lastname = $answer->getText();

            $this->createCustomer();
        });
    }

    public function createCustomer()
    {
        $this->customer = new Customer();
        $this->customer->setName($this->firstname);
        $this->customer->setLastname($this->lastname);
        $this->customer->setEmail($this->email);
        $this->askPassenger();
    }

    /*End of the Customer*/

    public function askPassenger()
    {
        $question = Question::create('How many passengers will you take?')
            ->callbackId('select_passengers')
            ->addButtons([
                Button::create('0')->value('1'),
                Button::create('1')->value('2'),
                Button::create('2')->value('3'),
                Button::create('3')->value('4'),
                Button::create('4')->value('5'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->passengers = $answer->getValue();
                $this->askVehicle();
            }
        });
    }

    public function askVehicle()
    {
        $question = Question::create('Will you take your car?')
            ->callbackId('select_vehicles')
            ->addButtons([
                Button::create('Yes')->value('1'),
                Button::create('No')->value('0'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->vehicle = $answer->getValue();
                $this->printInformation();
            }
        });
    }



    public function printInformation()
    {
        $message = '<br>=========================<br>';
        $message .= 'Selected Ferry: <br>';
        $message .= 'Starting Doc: ' . $this->ferry->getStartingDoc() . '<br>';
        $message .= 'Destination Doc: ' . $this->ferry->getDestinationDoc() . '<br>';
        $message .= 'Date: ' . $this->ferry->getDate()->format('M d H:i') . '<br>';
        $message .= '=========================<br><br>';
        $message .= 'Customer information: <br>';
        $message .= 'Name: ' . $this->customer->getName() . '<br>';
        $message .= 'Lastname: ' . $this->customer->getLastname() . '<br>';
        $message .= 'Email: ' . $this->customer->getEmail() . '<br>';
        $message .= 'Passengers: ' . $this->passengers . '<br>';
        $message .= 'Vehicles: ' . $this->vehicle . '<br>';
        $message .= '=========================<br>';


        $this->say('Here is your booking details.' . $message);
        //$this->finalizeReservation();
    }

    public function finalizeReservation()
    {
        $this->saveReservation();
    }

    public function saveReservation() {
      $this->reservation->setFerry($this->ferry);


      $this->reservation->calculateTotal();
      $this->getContainer()->get(DBService::class)->saveReservation($this->reservation);
    }

}