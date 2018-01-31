<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class SendVenue extends Method
{

	protected static $method = "sendVenue";

	protected static $map = [
		"chat_id" => true,
		"latitude" => true,
		"longitude" => true,
		"title" => true,
		"address" => true,
		"foursquare_id" => false,
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
	 * Name of the venue
	 * @var string
	 */
	protected $title;

	/**
	 * Address of the venue
	 * @var string
	 */
	protected $address;

	/**
	 * Foursquare identifier of the venue
	 * @var string
	 */
	protected $foursquareId;

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

	public function setLatitude(float $lat)
	{
		$this->latitude = $lat;
	}

	public function setLongitude(float $long)
	{
		$this->longitude = $long;
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
	}

	public function setaddress(string $address)
	{
		$this->address = $address;
	}

	public function setFoursquareId(string $id)
	{
		$this->foursquareId = $id;
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