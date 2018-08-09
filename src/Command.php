<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

abstract class Command extends ForwardableHandler
{
	/**
	 * Name of the command, i.e. /[name]
	 * @var string
	 */
	protected $name = null;

	/**
	 * Text to show when a user types /help, unless overridden by another Command.
	 * Leave blank to not show in the list.
	 * @var string
	 */
	protected $helpText = "";

	/**
	 * What to do when the command is called.
	 * @param  string $arguments Arguments entered by the user.
	 * @param \TelegramBot\Api\Types\Message $message Message object that triggered this command.
	 */
	abstract public function process(string $arguments, \TelegramBot\Api\Types\Message $message);

	public function getName(): string
	{
		return $this->name;
	}

	public function getHelpText(): ?string
	{
		return $this->helpText;
	}
}