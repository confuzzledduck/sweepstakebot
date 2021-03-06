<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use App\Conversations\SweepstakeSetupConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
	
	public function newSweepstake(BotMan $bot) {
        $bot->startConversation(new SweepstakeSetupConversation());
	}
	
	public function editSweepstake(BotMan $bot) {
        $bot->startConversation(new SweepstakeEditConversation());
	}
	
	public function viewSweepstake(BotMan $bot) {
        $bot->startConversation(new SweepstakeViewConversation());
	}
	
}
