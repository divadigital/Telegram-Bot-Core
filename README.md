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
	protected static $storageLocation = __DIR__; // Change if you intend to use Conversations

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
		$reply = $this->sendMessageReply();
		$reply->setText($arguments);
		$response = $reply->send(); // Get Telegram's response
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
$message = $this->sendMessageReply();
$message->setText("Hello World");

// Traditional way of setting up a keyboard. Helper functions are available.
// (See Keyboard section in the docs)
$keyboard = new InlineKeyboardMarkup([[["Click Me" => "https://google.com"]]]);

$message->setReplyMarkup($keyboard);
$message->send();
```

### TelegramBotCore

```php
abstract class TelegramBotCore {
	/**
	 * Token given by telegram for the bot.
	 * @var string
	 */
	protected static $token = null; // Override this

	/**
	 * Username of the bot, e.g. @exampleBot
	 * @var string
	 */
	protected static $username = null; // Override this

	/**
	 * Directory to store conversation files
	 * @var string
	 */
	protected static $storageLocation = __DIR__; // Optionally override this, but override if you are using Conversations

	/**
	 * Add all your commands and handlers within this function.
	 */
	abstract protected function registerHandlers();

	/**
	 * Call this function to turn on the bot when processing via webhooks.
	 */
	public static function webhook(): void
}
```

### Handlers - Commands

```php
abstract class Command extends ForwardableHandler {
	protected $name = null; // Override this
	protected $helpText = ""; // Optionally override this

	/**
	 * What to do when the command is called.
	 * @param  string $arguments Arguments entered by the user.
	 * @param \TelegramBot\Api\Types\Message $message Message object that triggered this command.
	 */
	abstract public function process($arguments, $message);
}
```

To create a new Command, extend `\KeythKatz\TelegramBotCore\Command`, and override
`$name` with the name of the command, which will be triggered by `/name`. Optionally override
`$helpText` to provide a help text which will be shown via the auto-generated `/help`. If it is
left as null or as an empty string, the command will not be shown in `/help`.

The `Command` class has helper functions, also linked to the Telegram API methods:
```php
// Create a new SendMessage linked to the bot.
$message = $this->sendMessage();

// Add Reply to any method name to create it with the chatId prefilled.
// Set $quoteOriginal to true to reply directly to the triggering message.
$message = $this->sendMessageReply($quoteOriginal = false);

// Forward the triggering message to another chat immediately.
// Set $disableNofication to true to send the message silently.
$this->forwardMessage($toChatId, $disableNotification = false);

// And other API methods...
```

From anywhere in the class, you can also interact directly with the bot or the triggering message using:
```php
$this->bot;
$this->message;
```

Add it to your bot's `registerHandlers` function like so:
```php
$this->addCommand(new EchoCommand());
```

### Handlers - CallbackQueryHandler

```php
abstract class CallbackQueryHandler extends BaseHandler {
	/**
	 * What to do when the bot receives a CallbackQuery.
	 * @param  CallbackQuery $query received CallbackQuery.
	 * @param  \TelegramBot\Api\Types\Message $message Message that the callback button originated from.
	 *                          May be null if the message is too old.
	 */
	abstract public function process(CallbackQuery $query, \TelegramBot\Api\Types\Message $message);
}
```

The `CallbackQueryHandler` works similarly to Commands. Override the `process()` function in your own class.

From anywhere in the class, you can also interact directly with the bot or the triggering query using:
```php
$this->bot;
$this->query;
```

Add it to your bot's `registerHandlers` function like so:
```php
$this->setCallbackQueryHandler(new CqHandler());
```

### Handlers - GenericMessageHandler

```php
abstract class GenericMessageHandler extends ForwardableHandler {
	/**
	 * What to do when the command is called.
	 * @param  string $arguments Arguments entered by the user.
	 * @param \TelegramBot\Api\Types\Message $message Message object that triggered this command.
	 */
	abstract public function process(\TelegramBot\Api\Types\Message $message);
}
```

The `GenericMessageHandler` works similarly to Commands. Override the `process()` function in your own class.

From anywhere in the class, you can also interact directly with the bot or the triggering message using:
```php
$this->bot;
$this->message;
```

Add it to your bot's `registerHandlers` function like so:
```php
$this->setGenericMessageHandler(new DefaultMessageHandler());
```

### Keyboards
`InlineKeyboardMarkup` and `ReplyKeyboardMarkup` have helper functions.

There are two ways to create a keyboard. The first is direct creation:
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

// Add a new row. Added buttons after this will be on the new row.
$keyboard->addRow();

// Add a button to any row, 0-indexed
$keyboard->addButton($button, 0);
```

### Conversations

```php
abstract class Conversation extends ForwardableHandler {
	/**
	 * Chat ID that the conversation belongs to.
	 * @var int
	 */
	protected $chatId;

	/**
	 * ID of the user that initiated the conversation.
	 * @var int
	 */
	protected $userId;

	/**
	 * Initialise stages here by calling $this->addStage() for each stage.
	 */
	abstract public function initialize();

	/**
	 * Add a stage.
	 * @param string   $name     Name of the stage.
	 * @param string   $message  Message to send and ask a reply to.
	 * @param callable $callback Callback that is called with the replying message as a parameter.
	 */
	protected function addStage(string $name, string $message, callable $callback): void

	/**
	 * Save data that will be persistant across conversations.
	 * @param  string $name Name of the data.
	 * @param  anything $data Data to be stored.
	 */
	protected function saveData(string $name, $data): void

	/**
	 * Load saved data.
	 * @param  string $name Name of the data.
	 * @return anything       Saved data.
	 */
	protected function loadData(string $name)

	/**
	 * Repeat the current stage, for example on invalid input.
	 */
	protected function repeatStage(): void

	/**
	 * Set the current stage, i.e. move on in the conversation.
	 * @param string $name Name of the stage to move to.
	 */
	protected function setStage(string $name): void
}
```

Conversations allow input to be done over multiple messages. They are handled via "stages".
For each stage, provide a name, a message that is sent that is to be replied to by the user, and the callback
for the reply, which takes a `\TelegramBot\Api\Types\Message` object.
The first stage added will be the starting point of the conversation.

Data can be saved and loaded in the conversation.

Example:
```php
class ExampleConversation extends \KeythKatz\TelegramBotCore\Conversation {
	public function initialize() {
		$this->addStage("start", "Enter a message.",
			function($message) {
				if ($message->getText() === null) {
					// No text found, show an error and repeat the question.
					$reply = $this->sendMessageReply();
					$reply->setText("You did not enter anything.");
					$reply->send();
					$this->repeatStage();
				} else {
					$this->saveData("message 1", $message->getText());
					$this->setStage("next message");
				}
			}
		);

		$this->addStage("next message", "Enter another message.",
			function($message) {
				if ($message->getText() === null) {
					// No text found, show an error and repeat the question.
					$reply = $this->sendMessageReply();
					$reply->setText("You did not enter anything.");
					$reply->send();
					$this->repeatStage();
				} else {
					$text1 = $this->loadData("message 1");
					$text2 = $message->getText();
					$reply = $this->sendMessageReply();
					$reply->setText("You entered $text1 and $text2");
					$reply->send();
				}
			}
		);
	}
}
```

Call `$this->startConversation(new ExampleConversation())` from any handler, including other conversations, to start it.

```
Bot: Enter a message.
User: 123
Bot: Enter another message.
User: abc
Bot: You entered 123 and abc
```

### InputFile
Anywhere where `InputFile` is specified in the Telegram API, you may just send
a resource, e.g. `fopen($file, 'r')`. Alternatively, you may encapsulate it in a
`InputFile` class, e.g. `new InputFile(fopen($file, 'r'))`.

### Async
This library supports synchronous sending of messages via `Guzzle/Promises`.
To send a message asynchronously, just change `send()` to `sendAsync()`.
Both methods return Telegram's response, whatever it is according to the Telegram API.

## Checklist

### Bot functionality
- [x] Process Commands
- [x] Handle CallbackQueries
- [x] Process generic messages without command
- [x] Block user

### Methods
- [x] sendMessage
- [x] forwardMessage
- [x] sendPhoto
- [x] sendAudio
- [x] sendDocument
- [x] sendVideo
- [x] sendVoice
- [x] sendVideoNote
- [x] sendMediaGroup
- [x] sendLocation
- [x] editMessageLiveLocation
- [x] stopMessageLiveLocation
- [x] sendVenue
- [x] sendContact
- [x] sendChatAction
- [x] getFile
- [ ] ... chat and sticker functions out of scope for now
- [x] answerCallbackQuery

### Types
- [x] ReplyKeyboardMarkup
- [x] KeyboardButton
- [x] ReplyKeyboardRemove
- [x] InlineKeyboardMarkup
- [x] InlineKeyboardButton
- [x] ForceReply
- [x] InputMedia
- [x] InputMediaPhoto
- [x] InputMediaVideo
- [X] InputFile

Types that are receive-only (from an Update object) are handled by `telegram-bot/api` and are not tracked.