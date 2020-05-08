<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeSelectOptionConversation extends SweepstakeSubConversationAbstract
{

	/**
	* Start the conversation
	*/
	public function run() {

		$this->say('Select option conversation.');
		//$this->askQuestion();

	}

}
