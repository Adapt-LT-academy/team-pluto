<?php
/**
 * Created by PhpStorm.
 * User: mindaho
 * Date: 18.12.2
 * Time: 13.00
 */

namespace App\Service;


use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use DateTime;


class FerryOrderConversation extends Conversation
{
    protected $destination;

    protected $date;

    protected $time;

    protected $firstname;

    protected $lastname;

    protected $email;

    protected $passengers;

    protected $vehicle;


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
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'yes'){
                    $this->askDestination();
                }
                //$selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                //$selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
            }
        });
    }

    public function askDestination()
    {
        $question = Question::create('Select destination')
            ->callbackId('select_time')
            ->addButtons([
                Button::create('Klaipėda-Ryga')->value('ryga'),
                Button::create('Klaipėda-Talinas')->value('talinas'),
                Button::create('Klaipėda-Kylis')->value('kylis'),
            ]);
        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->destination = $answer->getValue();
                $this->askDate();
            }
        });
    }

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
            }
        });
    }

    public function printInformation()
    {

        $message = '=========================<br>';
        $message .='Selected Ferry: <br>';
        $message .='Destination: ' . $this->destination .'<br>';
        $message .='Date: ' . $this->date .'<br>';
        $message .='Time: ' . $this->time .'<br>';
        $message .='=========================<br><br>';
        $message .='Customer information: <br>';
        $message .='Name: ' . $this->firstname .'<br>';
        $message .='Lastname: ' . $this->lastname .'<br>';
        $message .='Email: ' . $this->email .'<br>';
        $message .='Passengers: ' . $this->passengers .'<br>';
        $message .='Vehicles: ' . $this->vehicle .'<br>';
        $message .= '=========================<br>';

        $this->say('Here is your booking details.' .$message);
    }

    public function run()
    {
        // This will be called immediately
        $this->startingQuestion();
    }
}