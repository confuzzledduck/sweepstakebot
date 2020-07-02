<?php
use App\Http\Controllers\BotManController;
use App\Http\Controllers\SweepstakeBotController;

$botman = resolve('botman');

// Routes for sweepstake functionality...
$botman->hears('New', BotManController::class.'@newSweepstake');
$botman->hears('Edit', BotManController::class.'@editSweepstake');
$botman->hears('View', BotManController::class.'@viewSweepstake');