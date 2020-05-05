<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeSetupOptionsConversation extends Conversation
{

	protected $_options = array();

	/**
	* Start the conversation
	*/
	public function run() {

		$this->say('Setup options conversation.');
		$this->askForOptions();

	}
	
	public function askForOptions() {
	
		//
	
	}

}
