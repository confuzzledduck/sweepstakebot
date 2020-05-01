# Sweepstake Bot

## About

Sweepstake Bot is a chatbot, built upon BotMan Studio and Laravel, to run sweepstakes. Primarily designed for use with Slack to enable workplace sweepstakes, it should work with any of the chat frameworks supported by BotMan.

## Game Types

It currently supports three types of game:
- Enter your own option type games. Designed for use in games such as baby weight sweepstakes, guess the date, sweets in a jar and that kind of thing. In these games each user can enter a value of their own and the winning player is decided based on this choice. Options in this kind of game include winning criteria (closest guess, exact match) and bounds to the guesses.
- Choose an option from a given list. Useful for sporting events, like the soccer World Cup or tennis grand slams, or other external competitions with a set number of participants such as Eurovision. Options here include limiting the number of choices each player can make, allowing options to be picked by more than one player or just one and  what to do with options which are not selected by any player.
 - Be allocated an option from a given list. Useful for the same as the above, but where you want people to get one or more random entries. This kind of game is designed to simulate picking an option out of a hat.
