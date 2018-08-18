<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class EditMessageReplyMarkup extends Method
{

	protected static $method = "editMessageReplyMarkup";

	protected static $map = [
		"chat_id" => false,
		"message_id" => false,
		"inline_message_id" => false,
		"reply_markup" => false
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

	/**
	 * Required if chat_id and message_id are not specified. Identifier of the inline message.
	 * @var string
	 */
	protected $inlineMessageId;

	/**
	 * A JSON-serialized object for an inline keyboard.
	 * @var InlineKeyboardMarkup
	 */
	protected $replyMarkup;

	public function setChatId($id)
	{
		$this->chatId = $id;
	}

	public function setMessageId(int $id) {
		$this->messageId = $id;
	}

	public function setInlineMessageId(string $id) {
		$this->inlineMessageId = $id;
	}

	public function setReplyMarkup($r)
	{
		$this->replyMarkup = $r;
	}
}