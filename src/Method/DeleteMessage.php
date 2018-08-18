<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class DeleteMessage extends Method
{

	protected static $method = "deleteMessage";

	protected static $map = [
		"chat_id" => false,
		"message_id" => false
	];

	/**
	 * Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format @channelusername).
	 * @var int|string
	 */
	protected $chatId;

	/**
	 * Required if inline_message_id is not specified. Identifier of the sent message.
	 * @var int
	 */
	protected $messageId;

	public function setChatId($id)
	{
		$this->chatId = $id;
	}

	public function setMessageId(int $id) {
		$this->messageId = $id;
	}
}