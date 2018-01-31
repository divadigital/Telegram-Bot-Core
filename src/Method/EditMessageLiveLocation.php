<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class EditMessageLiveLocation extends Method
{

	protected static $method = "editMessageLiveLocation";

	protected static $map = [
		"chat_id" => false,
		"latitude" => true,
		"longitude" => true,
		"message_id" => false,
		"inline_message_id" => false,
		"reply_markup" => false
	];

	/**
	 * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
	 * @var int|string
	 */
	protected $chatId;

	/**
	 * Latitude of the location
	 * @var float
	 */
	protected $latitude;

	/**
	 * Longitude of the location
	 * @var float
	 */
	protected $longitude;

	/**
	 * Required if inline_message_id is not specified. Identifier of the sent message
	 * @var int
	 */
	protected $messageId;

	/**
	 * Required if chat_id and message_id are not specified. Identifier of the inline message
	 * @var string
	 */
	protected $inlineMessageId;

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

	public function setLatitude(float $lat)
	{
		$this->latitude = $lat;
	}

	public function setLongitude(float $long)
	{
		$this->longitude = $long;
	}

	public function setMessageId(int $id)
	{
		$this->messageId = $id;
	}

	public function setInlineMessageId(int $id)
	{
		$this->inlineMessageId = $id;
	}

	public function setReplyMarkup($r)
	{
		$this->replyMarkup = $r;
	}
}