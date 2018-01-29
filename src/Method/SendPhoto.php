<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\Type\InputFile;

class SendPhoto extends Method
{

	protected static $method = "sendPhoto";

	protected static $map = [
		"chat_id" => true,
		"photo" => true,
		"caption" => false,
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
	 * Photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended),
	 * pass an HTTP URL as a String for Telegram to get a photo from the Internet,
	 * or upload a new photo using multipart/form-data.
	 * @var resource|string
	 */
	protected $photo;

	/**
	 * Photo caption (may also be used when resending photos by file_id), 0-200 characters
	 * @var string
	 */
	protected $caption;

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

	/**
	 * Set the param.
	 * @param resource|string $photo If string, sets the URL of the file. Pass a resource (e.g. fopen()) to upload the file.
	 */
	public function setPhoto($photo)
	{
		if (is_string($photo)) {
			$this->photo = $photo;
		} else if (is_resource($photo)) {
			$this->addMultipart("photo", $photo);
			$this->photo = "attach://photo";
		} else if ($photo instanceof InputFile) {
			$this->addMultipart("photo", $photo->getFile());
			$this->photo = "attach://photo";
		}
	}

	public function setCaption(string $caption)
	{
		$this->caption = $caption;
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