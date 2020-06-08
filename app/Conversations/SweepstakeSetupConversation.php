<?php

namespace App\Conversations;

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
						$this->askEnding();
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
				
				$optionCount = count($optionList);
				$this->say('Thanks. I\'ll add '.number_format($optionCount).' new '.(($optionCount > 1) ? 'options' : 'option').' to this game.');
				
				// Save the options into the database...
				foreach ($optionList AS $optionValue) {
					$optionEntry = new \App\SweepstakeOption;
					$optionEntry->sweepstake_id = $this->_sweepstakeModel->id;
					$optionEntry->option = $optionValue;
					$optionEntry->save();
				}
				
				// And move on to dates...
				$this->askEnding();
			
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
	
	public function askEnding() {
		
		$this->say('All games come to an end. Either a set date is reached (like a sporting event\'s final) or some real-life event takes place at an unpredictable time (like having a baby). You need to choose which type this game is.');
		$this->say('If you choose a fixed end time, I\'ll remind you to give me the winning answers after that point. If you choose a variable end time you\'ll need to tell me when the game is over and choose the winning answers then.');
		$this->say('Don\'t forget, you can also end the game at any time by telling me to finish it early.');

		$question = Question::create('So, what kind of ending does this game have?')
			->fallback('Unable to ask question')
			->callbackId('ask_ending')
			->addButtons([
				Button::create('A known fixed end date and time.')->value('fixed'),
				Button::create('An as yet unknown end date and time.')->value('variable')
			]);

		$this->ask($question, function (Answer $answer) {
			if ($answer->isInteractiveMessageReply()) {
				if ($answer->getValue() == 'fixed') {
					$this->askEndDate();
				} else if ($answer->getValue() == 'variable') {
					$this->say('OK. You\'ll need to tell me when this game ends and the results are available.');
					$this->_sweepstakeModel->end_date = null;
					$this->_sweepstakeModel->save();
					$this->askStart();
				} else {
					// Error
				}
			}
		});
		
	}
	
	public function askEndDate() {

		return $this->ask('When do you want this game to end?', function (Answer $answer) {

			// Parse the end date...
			if ($endDate = strtotime($answer->getText())) {
				if ($endDate > time()) {
					// Save the end date to the database...
					$this->_sweepstakeModel->end_date = date('Y-m-d H:i:s', $endDate);
					$this->_sweepstakeModel->save();
					$this->say('No problem. The game will end on the '.date('jS F \a\t g:ia', $endDate).'.');
					$this->askStart();
				} else {
					$this->say('Sorry, I can\'t schedule a game to end in the past. Can you try again?');
					$this->askEndDate();
				}
			} else {
				$this->say('I didn\'t understand the date you gave me. Can you try again?');
				$this->askEndDate();
			}

			// Move on to ask about the game start date...
			$this->askStart();

		});
		
	}
	
	public function askStart() {

		$this->say('I actually have another question about dates and times.');

		$question = Question::create('When do you want this game to start?')
			->fallback('Unable to ask question')
			->callbackId('ask_starting')
			->addButtons([
				Button::create('Immediately. Players can start to enter straight away.')->value('immediate'),
				Button::create('Some other point in the future. I only want people to be able to start playing after a certain date and/or time.')->value('later')
			]);

		$this->ask($question, function (Answer $answer) {
			if ($answer->isInteractiveMessageReply()) {
				if ($answer->getValue() == 'immediate') {
					$this->say('Right, people will be able to start playing immediately.');
					$this->_sweepstakeModel->start_date = null;
					$this->_sweepstakeModel->save();
					$this->closingStatements();
				} else if ($answer->getValue() == 'later') {
					$this->askStartDate();
				} else {
					// Error
				}
			}
		});

	}

	public function askStartDate() {

		return $this->ask('What is the starting date (and time, if you want) for the game?', function (Answer $answer) {

			// Parse the start date...
			if ($startDate = strtotime($answer->getText())) {
				if ($startDate > time()) {
					// Save the start date to the database...
					$this->_sweepstakeModel->start_date = date('Y-m-d H:i:s', $startDate);
					$this->_sweepstakeModel->save();
					$this->say('Okay. The game will start on the '.date('jS F \a\t g:ia', $startDate).'.');
				} else {
					$this->say('I can\'t start a game in the past. Please try again.');
					$this->askStartDate();
				}
			} else {
				$this->say('I didn\'t understand the date you gave me. Can you try again?');
				$this->askStartDate();
			}

			// Move on to the closing statements...
			$this->closingStatements();

		});

	}

	public function closingStatements() {

		//...

	}

}