<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class ForwardMessage extends Method
{

	protected static $method = "forwardMessage";

	protected static $map = [
		"chat_id" => true,
		"from_chat_id" => true,
		"disable_notification" => false,
		"message_id" => true
	];

	/**
	 * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
	 * @var int|string
	 */
	protected $chatId;

	/**
	 * Unique identifier for the chat where the original message was sent (or channel username in the format @channelusername)
	 * @var int|string
	 */
	protected $fromChatId;

	/**
	 * Sends the message silently. Users will receive a notification with no sound.
	 * @var bool
	 */
	protected $disableNotification = false;

	/**
	 * Message identifier in the chat specified in from_chat_id
	 * @var int
	 */
	protected $messageId;

	public function setChatId($id)
	{
		$this->chatId = $id;
	}

	public function setFromChatId($id)
	{
		$this->fromChatId = $id;
	}

	public function setDisableNotification(bool $d)
	{
		$this->disableNotification = $d;
	}

	public function setMessageId(int $id)
	{
		$this->messageId = $id;
	}
}