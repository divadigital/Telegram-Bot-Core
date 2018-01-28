# Telegram Bot Core

Object-Oriented Bot Framework for Telegram adhering to the [Telegram Bot API](https://core.telegram.org/bots/api).

## Requirements
- PHP 7.2 and above

## Installation

With [Composer](https://getcomposer.org/):

```
composer require keythkatz/telegram-bot-core
```

## Quickstart

### Create a bot

```php
class ExampleBot extends \KeythKatz\TelegramBotCore\TelegramBotCore {
	protected static $token = "your telegram API token here";
	protected static $username = "@YourBotsUsername";

	protected function registerHandlers()
	{
		// We will fill in this part later
	}
}
```

### Commands

Each Command is its own class. Here we create a simple echo command.

```php
class EchoCommand extends \KeythKatz\TelegramBotCore\Command
{
	// Name of the command, /echo
	protected $name = "echo";

	// What text to show for the command when doing /help.
	// Set to null to hide the command.
	protected $helpText = "Repeat after me";
	
	/**
	 * What to do when the command is called.
	 * @param  string $arguments The arguments following the command as a string.
	 * @param  Message $message Raw Message object that triggered this command.
	 */
	public function process($arguments, $message)
	{
		$reply = $this->createMessageReply();
		$reply->setText($arguments);
		$reply->send();
	}
}
```

Next, add the command in your bot's `registerHandlers()`:
```php
protected function registerHandlers()
{
	$this->addCommand(new EchoCommand());
}
```

### Set up a Webhook
From wherever you told Telegram to send you updates, call your bot:
```php
ExampleBot::webhook();
```

That's it! Now `/echo` and `/help` should work.

## Docs

### Methods and Types, the Telegram API

This library follows the official Telegram API docs. Classes under the `Method` namespace
follow the [Available methods](https://core.telegram.org/bots/api#available-methods) section of
the official API. Classes under the `Type` namespace follow [Available types](https://core.telegram.org/bots/api#available-types).

Parameters in Telegram are in snake_case. This library uses camelCase, however all the names are the same. To set a parameter, use setters.

This library uses the [telegram-bot/api](https://github.com/TelegramBot/Api) library for parsing received Updates. Types in both
libraries work the same way, with getters and setters.

Example: Sending an inline keyboard from a command

```php
$message = $this->createMessageReply();
$message->setText("Hello World");

// Traditional way of setting up a keyboard. Helper functions are available.
// (See Keyboard section in the docs)
$keyboard = new InlineKeyboardMarkup([[["Click Me" => "https://google.com"]]]);

$message->setReplyMarkup($keyboard);
$message->send();
```

**Note: methods and types are still being implemented. The checklist is available [here](#checklist).**

### The `Command` class

The `Command` class has a few helper functions, also linked to the Telegram API methods:
```php
// Create a new SendMessage linked to the bot.
$this->sendMessage();

// Create a new SendMessage with the chatId prefilled.
// Set $quoteOriginal to true to reply directly to the triggering message.
$this->sendMessageReply($quoteOriginal = false);

// Forward the triggering message to another chat.
// Set $disableNofication to true to send the message silently.
$this->forwardMessage($toChatId, $disableNotification = false);
```

From anywhere in the class, you can also interact directly with the bot or the triggering message using:
```php
$this->bot;
$this->message;
```

### Keyboards
`InlineKeyboardMarkup` and `ReplyKeyboardMarkup` have helper functions.

There are two ways to create a keyboard. The first is directly creation:
```php
$keyboard = new InlineKeyboardMarkup([[["Click Me" => "https://google.com"]]]);
// or
$keyboard = new InlineKeyboardMarkup();
$keyboard->setInlineKeyboard([[["Click Me" => "https://google.com"]]]);
```

The second way is to use helper functions:
```php
$keyboard = new InlineKeyboardMarkup(); // or ReplyKeyboardMarkup

// Create a new button
$button = new InlineKeyboardButton($text); // or KeyboardButton
$button->setCallbackData("12345"); // or any other field in the telegram docs

// Add to the keyboard
$keyboard->addButton($button);

// Add a new row
$keyboard->addRow();

// Add a button to any row, 0-indexed
$keyboard->addButton($button, 0);
```

## Checklist

### Methods
- [x] sendMessage
- [x] forwardMessage
- [ ] sendPhoto
- [ ] sendAudio
- [ ] sendDocument
- [ ] sendVideo
- [ ] sendVoice
- [ ] sendVideoNote
- [ ] sendMediaGroup
- [ ] sendLocation
- [ ] editMessageLiveLocation
- [ ] stopMessageLiveLocation
- [ ] ... and so on

### Types
- [x] ReplyKeyboardMarkup
- [x] KeyboardButton
- [x] ReplyKeyboardRemove
- [x] InlineKeyboardMarkup
- [x] InlineKeyboardButton
- [x] ForceReply
- [ ] InputMedia
- [ ] InputMediaPhoto
- [ ] InputMediaVideo
- [ ] InputFile

Types that are receive-only (from an Update object) are handled by `telegram-bot/api` and are not tracked.