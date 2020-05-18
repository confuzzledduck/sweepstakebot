<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

use App\Sweepstake;

class SweepstakeSetupConversation extends Conversation
{

	// Sweepstake model instance variable...
	protected $_sweepstakeModel;

	/**
	* Start the conversation
	*/
	public function run() {
	
		// Create the new Sweepstake model instance...
		$this->_sweepstakeModel = new Sweepstake;

		// Set the sweep's owner...
        $this->_sweepstakeModel->owner = $this->bot->getUser()->getId();
		
		// And move on to asking the question...
		$this->say('OK. Let\'s start a new sweepstake. I need to know some stuff to get started.');
		$this->askTitle();

	}

	public function askTitle() {

		$this->say('The first thing to set up is the title of the sweepstake. It could be a question or it could just be a title, it kind of depends on what the game is.');
		$this->say('It might be a sporting event, so the title might just be "The Olympics". Or it might be a baby sweepstake, so the title might be a question like "How much will Jenny\'s baby weigh?" It\' up to you.');

		return $this->ask('So what do you want the sweepstake\'s title to be?', function (Answer $answer) {
			$this->say('OK. The title of this game is "'.$answer->getText().'".');
			
			// Add the name to the model...
			$this->_sweepstakeModel->name = $answer->getText();
			
			$this->askType();
		});

	}
	
	public function askType() {
	
		$question = Question::create('And what kind of sweepstake do you want it to be?')
			->fallback('Unable to ask question')
			->callbackId('ask_reason')
			->addButtons([
				Button::create('Each player selects their own value (for something like a like a baby weight sweepstake).')->value('value'),
				Button::create('An option from a fixed list is chosen at random for each player.')->value('option_random'),
				Button::create('Each player can select an option from a fixed list.')->value('option_select')
			]);

		return $this->ask($question, function (Answer $answer) {
			if ($answer->isInteractiveMessageReply()) {
				
				switch ($answer->getValue()) {
					case 'value':
						$this->bot->startConversation(new SweepstakeValuesConversation($this->_sweepstakeModel));
						break;
					case 'option_random':
						$this->bot->startConversation(new SweepstakeRandomOptionConversation($this->_sweepstakeModel));
						break;
					case 'option_select':
						$this->bot->startConversation(new SweepstakeSelectOptionConversation($this->_sweepstakeModel));
						break;
				}
			}
		});

	}
		
}
