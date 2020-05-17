<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Log;

class SweepstakeSetupOptionsConversation extends SweepstakeSubConversationAbstract
{

	/**
	* Start the conversation
	*/
	public function run() {

		$this->say('Setup options conversation.');
		$this->optionsPreamble();
		$this->askForOptions();

	}

	public function optionsPreamble() {

		$this->say('In these kinds of games, you need to give users a list of options.');
		$this->say('Don\'t forget, you can add, edit and remove options at any time before the game begins. but once it\'s started you can\'t make any more changes.');

	}
	
	public function askForOptions() {

		$this->say('Possible options should be provided as a comma separated list, ie. "option one, option two, etc.".');
		return $this->ask('Tell me the valid options in this game.', function (Answer $answer) {
			
			// Fetch the answer, which should be a CSV-style list...
			if (strlen($answer->getText()) > 0) {

				$optionList = str_getcsv($answer->getText());
				
				$this->say('Thanks. I\'ll add '.number_format(count($optionList)).' new options to this game.');
				
				// Save the options into the database...
				foreach ($optionList AS $optionValue) {
					$optionEntry = new \App\SweepstakeOption;
					$optionEntry->sweepstake_id = $this->_sweepstakeModel->id;
					$optionEntry->option = $optionValue;
					$optionEntry->save();
				}
			
			} else {

				$question = Question::create('I didn\'t recognise any options in your last message. Do you want to try again?')
					->fallback('Unable to ask question')
					->callbackId('ask_reason')
					->addButtons([
						Button::create('Yes, please.')->value('yes'),
						Button::create('No. I want to stop building this game.')->value('no')
					]);

				return $this->ask($question, function (Answer $answer) {
					if ($answer->isInteractiveMessageReply()) {
						if ($answer->getValue() == 'yes') {
							$this->askForOptions();
						} else {
							// TODO: Abort procedure.
							$this->say('Abort');
						}
					}
				});

			}
			
		});
	
	}

}
