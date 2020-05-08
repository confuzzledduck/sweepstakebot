<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeSetupOptionsConversation extends SweepstakeSubConversationAbstract
{

	/**
	* Start the conversation
	*/
	public function run() {

		$this->say('Setup options conversation.');
		$this->askForOptions();

	}
	
	public function askForOptions() {
	
		$this->say('In these kinds of games, you need to give users a list of options.');
		$this->say('Don\'t forget, you can add, edit and remove options at any time before the game begins. but once it\'s started you can\'t make any more changes.');
		$this->say('Possible options should be provided as a comma separated list, ie. "option one, option two, etc.".');
		
		return $this->ask('So what are the valid options in this game?', function (Answer $answer) {
			
			// Fetch the answer, which should be a CSV-style list... 
			$optionList = str_getcsv($answer->getText());
			$optionCount = count($optionList);
			
			if ($optionCount > 0) {
				
				$this->say('Thanks. It looks like there are '.number_format($optionCount).' options in this game.');
				
				// Save the options into the database...
				foreach ($optionList AS $optionValue) {
					$optionEntry = new \App\SweepstakeOption;
					$optionEntry->sweepstake_id = $this->_sweepstakeModel->id;
					$optionEntry->option = $optionValue;
					$optionEntry->save();
				}
				
				$this->say(implode($optionList, ' !! '));
			
			}
			
		});
	
	}

}
