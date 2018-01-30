<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\Type\InputFile;

class SendVideo extends Method
{

	protected static $method = "sendVideo";

	protected static $map = [
		"chat_id" => true,
		"video" => true,
		"duration" => false,
		"width" => false,
		"height" => false,
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
	 * Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended),
	 * pass an HTTP URL as a String for Telegram to get a video from the Internet,
	 * or upload a new video using multipart/form-data.
	 * @var resource|string
	 */
	protected $video;

	/**
	 * Duration of sent video in seconds
	 * @var int
	 */
	protected $duration;

	/**
	 * Video width
	 * @var int
	 */
	protected $width;

	/**
	 * 	Video height
	 * @var int
	 */
	protected $height;

	/**
	 * Video caption (may also be used when resending photos by file_id), 0-200 characters
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
	 * @param resource|string $video If string, sets the URL of the file. Pass a resource (e.g. fopen()) to upload the file.
	 */
	public function setVideo($video)
	{
		if (is_string($video)) {
			$this->video = $video;
		} else if (is_resource($video)) {
			$this->addMultipart("video", $video);
			$this->video = "attach://video";
		} else if ($video instanceof InputFile) {
			$this->addMultipart("video", $video->getFile());
			$this->video = "attach://video";
		}
	}

	public function setDuration(int $duration)
	{
		$this->duration = $duration;
	}

	public function setWidth(int $width)
	{
		$this->width = $width;
	}

	public function setHeight(int $height)
	{
		$this->height = $height;
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