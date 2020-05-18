<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeRandomOptionConversation extends SweepstakeSubConversationAbstract
{

	/**
	* Start the conversation
	*/
	public function run() {

		// Add the type to the model, and then save it to the database...
		$this->_sweepstakeModel->type = 'option_random';
		$this->_sweepstakeModel->save();

		// Jump to the options setup conversation...
		$this->bot->startConversation(new SweepstakeSetupOptionsConversation($this->_sweepstakeModel));

	}

}
