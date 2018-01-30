<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\Type\InputFile;

class SendVideoNote extends Method
{

	protected static $method = "sendVideoNote";

	protected static $map = [
		"chat_id" => true,
		"video_note" => true,
		"duration" => false,
		"length" => false,
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
	 * Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended),
	 * or upload a new video using multipart/form-data.
	 * @var resource|string
	 */
	protected $videoNote;

	/**
	 * Duration of sent video in seconds
	 * @var int
	 */
	protected $duration;

	/**
	 * Video width and height
	 * @var int
	 */
	protected $length;

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
	 * @param resource|string $video If string, sets the URL of the file. Pass a resource (e.g. fopen()) to upload the file.
	 */
	public function setVideoNote($video)
	{
		if (is_string($video)) {
			$this->videoNote = $video;
		} else if (is_resource($video)) {
			$this->addMultipart("video", $video);
			$this->videoNote = "attach://video";
		} else if ($video instanceof InputFile) {
			$this->addMultipart("video", $video->getFile());
			$this->videoNote = "attach://video";
		}
	}

	public function setDuration(int $duration)
	{
		$this->duration = $duration;
	}

	public function setLength(int $length)
	{
		$this->length = $length;
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