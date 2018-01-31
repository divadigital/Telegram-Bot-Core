<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class SendContact extends Method
{

	protected static $method = "sendContact";

	protected static $map = [
		"chat_id" => true,
		"phone_number" => true,
		"first_name" => true,
		"last_name" => false,
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
	 * Contact's phone number
	 * @var string
	 */
	protected $phoneNumber;

	/**
	 * Contact's first name
	 * @var string
	 */
	protected $firstName;

	/**
	 * Contact's last name
	 * @var string
	 */
	protected $lastName;

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

	public function setPhoneNumber(string $number)
	{
		$this->phoneNumber = $number;
	}

	public function setFirstName(string $firstName)
	{
		$this->firstName = $firstName;
	}

	public function setLastName(string $lastName)
	{
		$this->lastName = $lastName;
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