<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\Type\InputFile;

class SendVoice extends Method
{

	protected static $method = "sendVoice";

	protected static $map = [
		"chat_id" => true,
		"voice" => true,
		"caption" => false,
		"duration" => false,
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
	 * Audio file to send. Pass a file_id as String to send a audio file that exists on the Telegram servers (recommended),
	 * pass an HTTP URL as a String for Telegram to get a audio file from the Internet,
	 * or upload a new audio file using multipart/form-data.
	 * @var resource|string
	 */
	protected $audio;

	/**
	 * Voice message caption, 0-200 characters
	 * @var string
	 */
	protected $caption;

	/**
	 *	Duration of the voice message in seconds
	 * @var int
	 */
	protected $duration;

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
	 * @param resource|string $audio If string, sets the URL of the file. Pass a resource (e.g. fopen()) to upload the file.
	 */
	public function setVoice($voice)
	{
		if (is_string($voice)) {
			$this->voice = $voice;
		} else if (is_resource($voice)) {
			$this->addMultipart("voice", $voice);
			$this->voice = "attach://voice";
		} else if ($voice instanceof InputFile) {
			$this->addMultipart("voice", $voice->getFile());
			$this->voice = "attach://audio";
		}
	}

	public function setCaption(string $caption)
	{
		$this->caption = $caption;
	}

	public function setDuration(int $duration)
	{
		$this->duration = $duration;
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