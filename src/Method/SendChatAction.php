<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class SendChatAction extends Method
{

	protected static $method = "sendChatAction";

	protected static $map = [
		"chat_id" => true,
		"action" => true
	];

	/**
	 * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
	 * @var int|string
	 */
	protected $chatId;

	/**
	 * Type of action to broadcast. Choose one, depending on what the user is about to receive:
	 * typing for text messages, upload_photo for photos, record_video or upload_video for videos,
	 * record_audio or upload_audio for audio files, upload_document for general files,
	 * find_location for location data, record_video_note or upload_video_note for video notes.
	 * @var string
	 */
	protected $action;

	public function setChatId($id)
	{
		$this->chatId = $id;
	}

	public function setAction(string $action)
	{
		$this->action = $action;
	}

	protected function decodeResult($result)
	{
		return $result;
	}
}