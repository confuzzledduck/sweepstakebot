<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeSetupConversation extends Conversation
{

	protected $_type = null;

		/**
		* Start the conversation
		*/
		public function run() {
		
			$this->say('OK. Let\'s start a new sweepstake. I need to know some stuff to get started.');
			$this->askQuestion();
			//$this->askType();
				
    }

    public function askQuestion() {

			$this->say('The first thing to set up is the title of the sweepstake. It could be a question or it could just be a title, it kind of depends on what the game is.');
			$this->say('It might be a sporting event, so the title might just be "The Olympics". Or it might be a baby sweepstake, so the title might be a question like "How much will Jenny\'s baby weigh?" It\' up to you.');

			return $this->ask('So what do you want the sweepstake\'s title to be?', function (Answer $answer) {
				$this->_question = $answer->getText();
				$this->say('OK. The title of this game is "'.$answer->getText().'".');
				$this->askType();
			});

    }
	
    public function askType() {
	
			$question = Question::create('And what kind of sweepstake do you want it to be?')
				->fallback('Unable to ask question')
				->callbackId('ask_reason')
				->addButtons([
					Button::create('Each player selects their own value (for something like a like a baby weight sweepstake).')->value('values'),
					Button::create('Each player is assigned an option from a list I will provide.')->value('list'),
				]);

			return $this->ask($question, function (Answer $answer) {
				if ($answer->isInteractiveMessageReply()) {
					$this->_type = $answer->getValue();
					if ($answer->getValue() === 'values') {
						$this->say('Values, ay? Tricky.');
					} else {
						$this->say('Ah, a list.');
					}
				}
			});

		}
		
}
