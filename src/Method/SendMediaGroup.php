<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use TelegramBot\Api\Types\Message;

class SendMediaGroup extends Method
{

	protected static $method = "sendMediaGroup";

	protected static $map = [
		"chat_id" => true,
		"media" => true,
		"disable_notification" => false,
		"reply_to_message_id" => false
	];

	/**
	 * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
	 * @var int|string
	 */
	protected $chatId;

	/**
	 * A JSON-serialized array describing photos and videos to be sent, must include 2–10 items
	 * @var array
	 */
	protected $media;

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

	public function setChatId($id)
	{
		$this->chatId = $id;
	}

	/**
	 * Set the param.
	 * @param array of (InputMediaPhoto|InputMediaVideo)
	 */
	public function setMedia(array $media)
	{	
		$counter = 0;
		$encodedMedia = [];
		foreach ($media as $obj) {
			$attachment = $obj->getMedia();
			if (is_resource($attachment)) {
				$this->addMultipart("attachment$counter", $attachment);
				$obj->setMedia("attach://attachment$counter");
				$counter++;
			}
			array_push($encodedMedia, $obj->toObject());
		}

		$this->media = json_encode($encodedMedia);
	}

	public function setDisableNotification(bool $d)
	{
		$this->disableNotification = $d;
	}

	public function setReplyToMessageId(int $id)
	{
		$this->replyToMessageId = $id;
	}

	protected function decodeResult(array $result)
	{
		$messages = [];
		foreach ($result as $m) {
			array_push($messages, Message::fromResponse($m));
		}
		return $messages;
	}
}