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


class ReservationService extends Conversation
{
    use ContainerAwareConversationTrait;

    /**
     * @var Ferry
     */
    protected $ferry;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Reservation
     */
    protected $reservation;

    public function __construct(Ferry $ferry, Customer $customer)
    {
        $this->ferry = $ferry;
        $this->customer = $customer;
    }

    public function run()
    {
        $this->reservation = new Reservation();
        $this->askPassenger();
    }

    public function askPassenger()
    {
        $question = Question::create('How many passengers will you take with yourself?')
            ->callbackId('select_passengers')
            ->addButtons([
                Button::create('Travel alone')->value('0'),
                Button::create('1')->value('1'),
                Button::create('2')->value('2'),
                Button::create('3')->value('3'),
                Button::create('4')->value('4'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->reservation->setPassengers($answer->getValue());
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
                $this->reservation->setVehicles($answer->getValue());
                $this->printInformation();
            }
        });
    }

    public function setCustomerToReservation()
    {
        $this->reservation->setCustomers($this->customer);
    }

    public function setFerryToReservation()
    {
        $this->reservation->setFerry($this->ferry);
    }

    public function printInformation()
    {
        $this->setCustomerToReservation();
        $this->setFerryToReservation();
        $this->reservation->calculateTotal();

        $message = '<br>=========================<br>';
        $message .= '    Selected Ferry<br>';
        $message .= 'Starting Doc: ' . $this->ferry->getStartingDoc() . '<br>';
        $message .= 'Destination Doc: ' . $this->ferry->getDestinationDoc() . '<br>';
        $message .= 'Date: ' . $this->ferry->getDate()->format('M d H:i') . '<br>';
        $message .= 'Price Per Passenger: ' . $this->ferry->getPricePerPassenger()/100 . '€<br>';
        if ($this->reservation->getVehicles() == 1) {
            $message .= 'Price Per Vehicle: ' . $this->ferry->getPricePerVehicle()/100 . '€<br>';
        }
        $message .= '=========================<br><br>';
        $message .= '    Customer information <br>';
        $message .= 'Name: ' . $this->customer->getName() . '<br>';
        $message .= 'Lastname: ' . $this->customer->getLastname() . '<br>';
        $message .= 'Email: ' . $this->customer->getEmail() . '<br>';
        $message .= 'Passengers: ' . $this->reservation->getPassengers() . '<br>';
        if ($this->reservation->getVehicles() == 1) {
            $message .= 'Vehicles: Yes<br>';
        } else {
            $message .= 'Vehicle: No<br>';
        }
        $message .= '=========================<br><br>';
        $message .= 'Total Price: ' . $this->reservation->getTotal() / 100  . '€<br>';


        $this->say('Here is your booking details:' . $message);
        $this->askConfirmation();
    }

    public function askConfirmation()
    {
        $question = Question::create('Confirm reservation?')
            ->callbackId('confirm_reservation')
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'yes') {
                    $this->finalizeReservation();
                }
            }
        });
    }

    public function finalizeReservation()
    {
        $this->reservation->setFerry($this->ferry);
        $this->reservation->setCustomers($this->customer);

        //$this->getContainer()->get(DBService::class)->saveReservation($this->reservation);
    }
}

