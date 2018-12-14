<?php

namespace App\Service;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Traits\ContainerAwareConversationTrait;

class FerryOrderConversation extends Conversation
{
    use ContainerAwareConversationTrait;

    /*
     * TODO check how many spaces are left free
     */

    protected $ferries;

    protected $startingDoc;

    protected $destinationDoc;

    public function run()
    {
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
        $buttons = [];

        $tempFerries = $this->getContainer()->get(DBService::class)->getAllStartingDocs();

        foreach ($tempFerries as $key=>$ferry)
        {
            $buttons[] = Button::create($ferry->getStartingDoc())->value($ferry->getStartingDoc());
        }

        $question = Question::create('Here are available starting docs. Chose one:')
            ->callbackId('select_startingDoc')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->startingDoc = $answer->getValue();
                $this->askDestinationDoc();
            }
        });
    }

    public function askDestinationDoc()
    {
        $buttons = [];

        $tempFerries = $this->getContainer()->get(DBService::class)->getAllDestinationDocs();

        foreach ($tempFerries as $key=>$ferry)
        {
            $buttons[] = Button::create($ferry->getDestinationDoc())->value($ferry->getDestinationDoc());
        }

        $question = Question::create('Here are available destinations. Chose one:')
            ->callbackId('select_destinationDoc')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->destinationDoc = $answer->getValue();
                $this->getAllFerries();
            }
        });
    }

    public function getAllFerries()
    {
        $this->ferries = $this->getContainer()->get(DBService::class)->getFerries($this->startingDoc, $this->destinationDoc);

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
            if ($answer->isInteractiveMessageReply()) {
                $this->say('To finish reservation we will need few more details.');

                $this->bot->startConversation(new CustomerService($this->ferries[$answer->getText()]));
            }
        });
    }

}