<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeValuesConversation extends SweepstakeSubConversationAbstract
{

	/**
	* Start the conversation
	*/
	public function run() {
		
		// Add the type to the model, and then save it to the database...
		$this->_sweepstakeModel->type = 'values';
		$this->_sweepstakeModel->save();

		$this->say('Values conversation.');
		$this->say($this->_sweepstakeModel->owner);		
//$this->askQuestion();

	}

}
