<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class SendMessage extends Method
{

	protected static $method = "sendMessage";

	protected static $map = [
		"chat_id" => true,
		"text" => true,
		"parse_mode" => false,
		"disable_web_page_preview" => false,
		"disable_notification" => false,
		"reply_to_message_id" => false,
		"reply_markup" => false
	];

	/**
	 * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
	 * @var int|string
	 */
	protected $chatId;

	/**
	 * 	Text of the message to be sent
	 * @var string
	 */
	protected $text;

	/**
	 * Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
	 * @var string
	 */
	protected $parseMode;

	/**
	 * Disables link previews for links in this message
	 * @var bool
	 */
	protected $disableWebPagePreview = false;

	/**
	 * Sends the message silently. Users will receive a notification with no sound.
	 * @var bool
	 */
	protected $disableNotification = false;

	/**
	 * If the message is a reply, ID of the original message
	 * @var int
	 */
	protected $replyToMessageId;

	/**
	 * Additional interface options. A JSON-serialized object for an inline keyboard,
	 * custom reply keyboard, instructions to remove reply keyboard or to force a reply from the user.
	 * @var InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply
	 */
	protected $replyMarkup;

	public function setChatId($id)
	{
		$this->chatId = $id;
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

	public function setDisableNotification(bool $d)
	{
		$this->disableNotification = $d;
	}

	public function setReplyToMessageId(int $id)
	{
		$this->replyToMessageId = $id;
	}

	public function setReplyMarkup($r)
	{
		$this->replyMarkup = $r;
	}
}