<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use KeythKatz\TelegramBotCore\Method\SendMessage;

abstract class Command
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
	 * Parent bot of the command. Set by the bot on command binding.
	 * @var TelegramBotCore
	 */
	protected $bot;

	/**
	 * Message that triggered this command.
	 * @var \TelegramBot\Api\Types\Message
	 */
	protected $message;

	/**
	 * What to do when the command is called.
	 * @param  string $arguments Arguments entered by the user.
	 * @param Message $message Message object that triggered this command.
	 */
	abstract public function process(string $arguments, \TelegramBot\Api\Types\Message $message);

	/**
	 * Set the bot that this command is linked to.
	 * @param TelegramBotCore $bot Bot that this command is linked to.
	 */
	public function setBot(TelegramBotCore $bot): void
	{
		$this->bot = $bot;
	}

	/**
	 * Set the message that triggered this command.
	 * @param \TelegramBot\Api\Types\Message $m The message that triggered this command.
	 */
	public function setMessage(\TelegramBot\Api\Types\Message $m): void
	{
		$this->message = $m;
	}

	/**
	 * Create a new blank message.
	 * @return SendMessage blank message.
	 */
	public function sendMessage(): SendMessage
	{
		return $this->bot->sendMessage();
	}

	/**
	 * Create a new blank message sending back to the chat
	 * @param  boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendMessage new message.
	 */
	public function sendMessageReply(bool $quoteOriginal = false): SendMessage
	{
		$m = $this->bot->sendMessage();
		$m->setChatId($this->message->getChat()->getId());

		if ($quoteOriginal) {
			$m->setReplyToMessageId($this->message->getMessageId());
		}

		return $m;
	}

	/**
	 * Forward the message that triggered this command.
	 * @param  int|string   $toChatId            Chat ID to forward to.
	 * @param  bool|boolean $disableNotification Disable notification for this message.
	 */
	public function forwardMessage($toChatId, bool $disableNotification = false): void
	{
		$m = $this->bot->forwardMessage();
		$m->setChatId($toChatId);
		$m->setFromChatId($this->message->getChat()->getId());
		$m->setMessageId($this->message->getMessageId());
		$m->setDisableNotification($disableNotification);

		$m->send();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getHelpText(): string
	{
		return $this->helpText;
	}
}