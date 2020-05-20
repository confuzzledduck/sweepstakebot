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
	public $_sweepstakeModel;

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
			
			// Move on to ask about the game type...
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
				
				// Add the type to the model, and then save it to the database...
				$this->_sweepstakeModel->type = $answer->getValue();
				$this->_sweepstakeModel->save();
				
				switch ($answer->getValue()) {
					case 'value':
						$this->askDates();
						break;
					case 'option_random':
					case 'option_select':
						$this->askOptions();
						break;
				}
				
			}
			
		});
		

	}
	
	public function askOptions() {
		
		// Preamble...
		$this->say('In these kinds of games, you need to give users a list of options.');
		$this->say('Don\'t forget, you can add, edit and remove options at any time before the game begins. but once it\'s started you can\'t make any more changes.');
		
		// Ask for options...
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
				
				// And move on to dates,,,
				$this->askDates();
			
			} else {

				$question = Question::create('I didn\'t recognise any options in your last message. Do you want to try again?')
					->fallback('Unable to ask question')
					->callbackId('ask_reason')
					->addButtons([
						Button::create('Yes, please.')->value('yes'),
						Button::create('No. I want to stop building this game.')->value('no')
					]);

				$this->ask($question, function (Answer $answer) {
					if ($answer->isInteractiveMessageReply()) {
						if ($answer->getValue() == 'yes') {
							$this->askOptions();
						} else {
							// TODO: Abort procedure.
							$this->say('Abort');
						}
					}
				});

			}
			
		});
		
	}
	
	public function askDates() {
		
		$this->say('Ask dates');
		
	}

}