<?php

namespace App\Service;

use App\Entity\Ferry;
use App\Entity\Reservation;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Traits\ContainerAwareConversationTrait;
use DateTime;

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
  protected $ferry;

    protected $startingDoc;

    protected $destinationDoc;










    protected $date;

    protected $time;

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
                $this->askDate();
            } else {
                $this->say('Sorry no ferries from selected location exists. Please try again.');
                $this->askStartingDoc();
            }
        });
    }

    /*
    public function askDestinationDoc()
    {
        //instead lets have buttons with availabe FROM places

        $question = Question::create('What is your destination?');

        $this->ask($question, function (Answer $answer) {
            $exists = $this->getContainer()->get(DBService::class)->isExistingDestination($this->$answer->getText());
            if ($exists) {
                $this->destinationDoc = $this->$answer->getText();

                $this->ferry = $this->getContainer()->get(DBService::class)->getFerry($this->startingDoc, $this->destinationDoc);

                $this->askType();
            } else {
                $this->say('Sorry no ferries to selected location exists. Please try again.');
                $this->askStartingDoc();
            }
        });
    }
    */

    public function askDate()
    {
        $availableDates = [
            new DateTime('next Monday'),
            new DateTime('next Thursday'),
            new DateTime('next Saturday')
        ];

        $question = Question::create('Select date')
            ->callbackId('select_date')
            ->addButtons([
                Button::create($availableDates[0]->format('M d'))->value($availableDates[0]->format('Y-m-d')),
                Button::create($availableDates[1]->format('M d'))->value($availableDates[1]->format('Y-m-d')),
                Button::create($availableDates[2]->format('M d'))->value($availableDates[2]->format('Y-m-d')),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->date = $answer->getValue();
                $this->askTime();
            }
        });
    }

    public function askTime()
    {
        $question = Question::create('Select time slot?')
            ->callbackId('select_destination')
            ->addButtons([
                Button::create('09:00')->value('09:00'),
                Button::create('13:00')->value('13:00'),
                Button::create('17:00')->value('17:00'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->time = $answer->getValue();
                $this->askFirstname();
            }
        });
    }

    /* Everything regarding Customer*/

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->askLastname();
        });
    }

    public function askLastname()
    {
        $this->ask('What is your lastname?', function(Answer $answer) {
            // Save result
            $this->lastname = $answer->getText();

            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->askPassenger();
        });
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
                $this->saveReservation();
            }
        });
    }

    public function printInformation()
    {

        $message = '=========================<br>';
        $message .= 'Selected Ferry: <br>';
//        $message .= 'Destination: ' . $this->destination . '<br>';
        $message .= 'Date: ' . $this->date . '<br>';
        $message .= 'Time: ' . $this->time . '<br>';
        $message .= '=========================<br><br>';
        $message .= 'Customer information: <br>';
        $message .= 'Name: ' . $this->firstname . '<br>';
        $message .= 'Lastname: ' . $this->lastname . '<br>';
        $message .= 'Email: ' . $this->email . '<br>';
        $message .= 'Passengers: ' . $this->passengers . '<br>';
        $message .= 'Vehicles: ' . $this->vehicle . '<br>';
        $message .= '=========================<br>';

        $this->say('Here is your booking details.' . $message);
    }

    public function saveReservation() {
      $this->reservation->setFerry($this->ferry);


      $this->reservation->calculateTotal();
      $this->getContainer()->get(DBService::class)->saveReservation($this->reservation);
    }

}