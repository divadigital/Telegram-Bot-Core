<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

abstract class GenericMessageHandler extends ForwardableHandler
{

	/**
	 * What to do when the command is called.
	 * @param  string $arguments Arguments entered by the user.
	 * @param Message $message Message object that triggered this command.
	 */
	abstract public function process(\TelegramBot\Api\Types\Message $message);
}