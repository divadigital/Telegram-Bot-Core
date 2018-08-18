<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class EditMessageText extends Method
{

	protected static $method = "editMessageText";

	protected static $map = [
		"chat_id" => false,
		"message_id" => false,
		"inline_message_id" => false,
		"text" => true,
		"parse_mode" => false,
		"disable_web_page_preview" => false,
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
	 * New text of the message.
	 * @var string
	 */
	protected $text;

	/**
	 * Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
	 * @var string
	 */
	protected $parseMode;

	/**
	 * Disables link previews for links in this message.
	 * @var bool
	 */
	protected $disableWebPagePreview = false;

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

	public function setText(string $text)
	{
		$this->text = $text;
	}

	public function setParseMode(string $pm)
	{
		$this->parseMode = $pm;
	}

	public function setDisableWebPagePreview(bool $d)
	{
		$this->disableWebPagePreview = $d;
	}

	public function setReplyMarkup($r)
	{
		$this->replyMarkup = $r;
	}
}