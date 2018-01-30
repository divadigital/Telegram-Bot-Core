<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;

class InputMediaPhoto extends BaseType
{
	protected static $map = [
		"type" => true,
		"media" => true,
		"caption" => false
	];

	/**
	 * Type of the result, must be photo
	 * @var "photo"
	 */
	protected $type = "photo";

	/**
	 * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended),
	 * pass an HTTP URL for Telegram to get a file from the Internet,
	 * or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name.
	 * @var string|resource
	 */
	protected $media;

	/**
	 * Optional. Caption of the photo to be sent, 0-200 characters
	 * @var string
	 */
	protected $caption;

	public function __construct($media = null)
	{
		if ($media !== null) {
			$this->media = $media;
		}
	}

	public function getMedia()
	{
		return $this->media;
	}

	/**
	 * Pass in a string or a resource, e.g. fopen(). See SendPhoto.
	 * @param string|resource $media
	 */
	public function setMedia($media)
	{
		$this->media = $media;
	}

	public function setCaption(string $caption)
	{
		$this->caption = $caption;
	}
}