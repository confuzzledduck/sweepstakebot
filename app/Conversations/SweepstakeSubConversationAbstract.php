<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

abstract class SweepstakeSubConversationAbstract extends Conversation
{

	// Instance variable to hold sweepstake database model instance...
	protected $_sweepstakeModel;

	function __construct($sweepstakeModel) {
	
		// Set database model to instance variable...
		$this->_sweepstakeModel = $sweepstakeModel;
		
	}

}
