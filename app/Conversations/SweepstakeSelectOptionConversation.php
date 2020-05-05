<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SweepstakeSelectOptionConversation extends Conversation
{

	protected $_sweepstakeModel;

	function __construct($sweepstakeModel) {

		$this->_sweepstakeModel = $sweepstakeModel;

	}

	/**
	* Start the conversation
	*/
	public function run() {

		$this->say('Select option conversation.');
		//$this->askQuestion();

	}

}
