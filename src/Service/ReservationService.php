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


    public function __construct(Ferry $ferry, Customer $customer, int $passengers, int $vehicle)
    {
        $this->ferry = $ferry;
        $this->customer = $customer;

        $this->reservation = new Reservation();
        $this->reservation->setCustomers($this->customer);
        $this->reservation->setFerry($this->ferry);
        $this->reservation->setPassengers($passengers);
        $this->reservation->setVehicles($vehicle);
        $this->reservation->calculateTotal();
    }

    public function run()
    {
        $this->printInformation();
    }

    public function printInformation()
    {
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
        $message .= 'Passengers: ' . ($this->reservation->getPassengers()) . '<br>';
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
                }else{
                    $this->say('Your reservation was canceled. Have a nice day!');
                    return true;}
            }
        });
    }

    public function finalizeReservation()
    {
        $this->getContainer()->get(DBService::class)->saveReservation($this->reservation, $this->customer->getId(), $this->ferry->getId());
        $this->say('Your reservation was successful, have a nice day!');
        return true;
    }
}

