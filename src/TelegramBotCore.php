<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Exception\ClientException;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\BotApi;
use KeythKatz\TelegramBotCore\Method\{
	SendMessage,
	ForwardMessage,
	SendPhoto,
	SendAudio,
	SendDocument,
	SendVideo,
	SendVoice,
	SendVideoNote,
	SendMediaGroup,
	SendLocation,
	EditMessageLiveLocation,
	StopMessageLiveLocation,
	SendVenue,
	SendContact,
	SendChatAction
};

abstract class TelegramBotCore
{
	/**
	 * Token given by telegram for the bot.
	 * @var string
	 */
	protected static $token = null;

	/**
	 * Username of the bot, e.g. @exampleBot
	 * @var string
	 */
	protected static $username = null;

	private $commands = [];
	private $promises = [];

	/**
	 * Add all your commands and handlers within this function.
	 */
	abstract protected function registerHandlers();

	final private function __construct()
	{
		// Check if bot was set up correctly
		if (static::$token === null) {
			throw new \Exception('$token not set');
		}

		if (static::$username === null) {
			throw new \Exception('$username not set');
		}
	}

	/**
	 * Call this function to turn on the bot when processing via webhooks.
	 */
	public static function webhook(): void
	{	
		$myBot = new static();
		if ($data = BotApi::jsonValidate(file_get_contents('php://input'), true)) {
			$update = Update::fromResponse($data);
			$myBot->processUpdate($update);
		}
	}

	/**
	 * Create a new SendMessage.
	 * @return SendMessage new blank SendMessage.
	 */
	public function sendMessage(): SendMessage
	{
		return new SendMessage(static::$token, $this);
	}

	/**
	 * Create a new ForwardMessage.
	 * @return ForwardMessage new blank ForwardMessage.
	 */
	public function forwardMessage(): ForwardMessage
	{
		return new ForwardMessage(static::$token, $this);
	}

	/**
	 * Create a new SendPhoto.
	 * @return SendPhoto new blank SendPhoto.
	 */
	public function sendPhoto(): SendPhoto
	{
		return new SendPhoto(static::$token, $this);
	}

	/**
	 * Create a new SendDocument.
	 * @return SendDocument new blank SendDocument.
	 */
	public function sendDocument(): SendDocument
	{
		return new SendDocument(static::$token, $this);
	}

	/**
	 * Create a new SendAudio.
	 * @return SendAudio new blank SendAudio.
	 */
	public function sendAudio(): SendAudio
	{
		return new SendAudio(static::$token, $this);
	}

	/**
	 * Create a new SendVideo.
	 * @return SendVideo new blank SendVideo.
	 */
	public function sendVideo(): SendVideo
	{
		return new SendVideo(static::$token, $this);
	}

	/**
	 * Create a new SendVoice.
	 * @return SendVoice new blank SendVoice.
	 */
	public function sendVoice(): SendVoice
	{
		return new SendVoice(static::$token, $this);
	}

	/**
	 * Create a new SendVideoNote.
	 * @return SendVideoNote new blank SendVideoNote.
	 */
	public function sendVideoNote(): SendVideoNote
	{
		return new SendVideoNote(static::$token, $this);
	}

	/**
	 * Create a new SendMediaGroup.
	 * @return SendMediaGroup new blank SendMediaGroup.
	 */
	public function sendMediaGroup(): SendMediaGroup
	{
		return new SendMediaGroup(static::$token, $this);
	}

	/**
	 * Create a new SendLocation.
	 * @return SendLocation new blank SendLocation.
	 */
	public function sendLocation(): SendLocation
	{
		return new SendLocation(static::$token, $this);
	}

	/**
	 * Create a new EditMessageLiveLocation.
	 * @return EditMessageLiveLocation new blank EditMessageLiveLocation.
	 */
	public function editMessageLiveLocation(): EditMessageLiveLocation
	{
		return new EditMessageLiveLocation(static::$token, $this);
	}

	/**
	 * Create a new StopMessageLiveLocation.
	 * @return StopMessageLiveLocation new blank StopMessageLiveLocation.
	 */
	public function stopMessageLiveLocation(): StopMessageLiveLocation
	{
		return new StopMessageLiveLocation(static::$token, $this);
	}

	/**
	 * Create a new SendVenue.
	 * @return SendVenue new blank SendVenue.
	 */
	public function sendVenue(): SendVenue
	{
		return new SendVenue(static::$token, $this);
	}

	/**
	 * Create a new SendContact.
	 * @return SendContact new blank SendContact.
	 */
	public function sendContact(): SendContact
	{
		return new SendContact(static::$token, $this);
	}

	/**
	 * Create a new SendChatAction.
	 * @return SendChatAction new blank SendChatAction.
	 */
	public function sendChatAction(): SendChatAction
	{
		return new SendChatAction(static::$token, $this);
	}

	/**
	 * Add a command to the list of commands.
	 * @param Command $handler Handler for the command.
	 */
	protected function addCommand(Command $handler): void
	{
		$handler->setBot($this);
		$command = $handler->getName();
		$this->commands[strtoupper($command)] = $handler;
	}

	/**
	 * Process the formatted update.
	 * @param  Update $update
	 */
	protected function processUpdate(Update $update): void
	{
		// Register all commands
		$this->registerHandlers();
		$this->registerHelpCommand();

		// Only care about messages
		if ($update->getMessage() !== null) {

			$message = $update->getMessage();

			// Parse for valid targeted command
			$rawMessage = $message->getText();
			if (substr($rawMessage, 0, 1) === '/') {

				list($command, $arguments) = $this->splitMessage($rawMessage);

				// Call handler
				if (isset($this->commands[$command])) {
					$handler = $this->commands[$command];
					$handler->setMessage($message);
					$handler->process($arguments, $message);
				}
			}
		}

		$this->finishPromises();

		echo "Everything will be okay";
	}

	public static function testhook(string $update): void
	{
		$myBot = new static();
		if ($data = BotApi::jsonValidate($update, true)) {
			$update = Update::fromResponse($data);
			$myBot->processUpdate($update);
		}
	}

	private function registerHelpCommand(): void
	{
		if (!isset($this->commands["HELP"])) {
			$this->addCommand(new HelpCommand($this->commands));
		}
	}

	public function addPromise(Promise $promise): void
	{
		array_push($this->promises, $promise);
	}

	private function finishPromises(): void
	{
		foreach ($this->promises as $promise) {
			try {
				$promise->wait();
			} catch (ClientException $e) {
				echo "<pre>" . $e->getMessage() . "\r\n</pre>";
			}
		}
	}

	private function splitMessage(string $rawMessage): array
	{
		// Extract command
		$firstSpace = stripos($rawMessage, ' ');
		if ($firstSpace === false) {
			$rawCommand = substr($rawMessage, 1);
		} else {
			$rawCommand = substr($rawMessage, 1, $firstSpace - 1);
		}

		// Remove own username from back of command if found
		if (($usernamePos = stripos($rawCommand, static::$username)) === false) {
			$command = strtoupper($rawCommand);
		} else {
			$command = strtoupper(substr($rawCommand, 0, $usernamePos));
		}

		// Extract arguments
		if ($firstSpace !== false) {
			$arguments = substr($rawMessage, $firstSpace + 1);
		} else {
			$arguments = null;
		}

		return [$command, $arguments];
	}
}