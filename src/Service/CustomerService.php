<?php

namespace App\Service;


use App\Entity\Customer;
use App\Entity\Ferry;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Traits\ContainerAwareConversationTrait;


class CustomerService extends Conversation
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

    public function __construct(Ferry $ferry){
        $this->ferry = $ferry;
    }

    public function run()
    {
        $this->customer = new Customer();
        $this->askEmail();
    }

    public function askEmail()
    {
        $this->ask('Please input your email.', function (Answer $answer) {
            if(filter_var($answer->getText(), FILTER_VALIDATE_EMAIL))
            {
                $customerExists = $this->getContainer()->get(DBService::class)->getCustomer($answer->getText());
                if($customerExists != null)
                {
                    $this->customer = $customerExists;
                    $this->say('Welcome back '. $this->customer->getName(). '!');
                    $this->continueToReservation();
                }
                else
                {
                    $this->say('You are first time user. Please fill in your details.');
                    $this->customer->setEmail($answer->getText());
                    $this->askFirstname();
                }
            }
            else
            {
                $this->repeat('Email was typed incorrectly, please try again.');
            }
        });
    }

    public function askFirstname()
    {
        $this->ask('What is your name?', function(Answer $answer) {
            $this->customer->setEmail($answer->getText());
            $this->askLastname();
        });
    }

    public function askLastname()
    {
        $this->ask('What is your surname?', function(Answer $answer) {
            $this->customer->setEmail($answer->getText());
            $this->continueToReservation();
        });
    }

    public function continueToReservation()
    {
        $this->bot->startConversation(new ReservationService($this->ferry, $this->customer));
    }

}