<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use GuzzleHttp\Promise\Promise;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\BotApi;
use KeythKatz\TelegramBotCore\Method\{
	SendMessage,
	ForwardMessage
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
	 * Create a new SendableMessage.
	 * @return SendableMessage new blank SendableMessage.
	 */
	public function createMessage(): SendMessage
	{
		return new SendMessage(static::$token, $this);
	}

	/**
	 * Create a new ForwardableMessage.
	 * @return ForwardableMessage new blank ForwardableMessage.
	 */
	public function createMessageForward(): ForwardMessage
	{
		return new ForwardMessage(static::$token, $this);
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
			$promise->wait();
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
			$arguments = substr($rawMessage, $firstSpace);
		} else {
			$arguments = null;
		}

		return [$command, $arguments];
	}
}