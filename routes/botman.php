<?php
use App\Http\Controllers\BotManController;
use App\Http\Controllers\SweepstakeBotController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('New', BotManController::class.'@newSweepstake');