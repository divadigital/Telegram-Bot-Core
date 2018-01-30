<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\Type\InputFile;

class SendAudio extends Method
{

	protected static $method = "sendAudio";

	protected static $map = [
		"chat_id" => true,
		"audio" => true,
		"caption" => false,
		"duration" => false,
		"performer" => false,
		"title" => false,
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
	 * Audio caption, 0-200 characters
	 * @var string
	 */
	protected $caption;

	/**
	 * 	Duration of the audio in seconds
	 * @var int
	 */
	protected $duration;

	/**
	 * Performer
	 * @var string
	 */
	protected $performer;

	/**
	 * Track name
	 * @var string
	 */
	protected $title;

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
	public function setAudio($audio)
	{
		if (is_string($audio)) {
			$this->audio = $audio;
		} else if (is_resource($audio)) {
			$this->addMultipart("audio", $audio);
			$this->audio = "attach://audio";
		} else if ($audio instanceof InputFile) {
			$this->addMultipart("audio", $audio->getFile());
			$this->audio = "attach://audio";
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

	public function setPerformer(string $performer)
	{
		$this->performer = $performer;
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
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