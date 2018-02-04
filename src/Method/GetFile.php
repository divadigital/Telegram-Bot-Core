<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use TelegramBot\Api\Types\File;

class GetFile extends Method
{

	protected static $method = "getFile";

	protected static $map = [
		"file_id" => true
	];

	/**
	 * File identifier to get info about
	 * @var string
	 */
	protected $fileId;

	public function setFileId(string $id)
	{
		$this->fileId = $id;
	}

	protected function decodeResult(array $result)
	{
		return File::fromResponse($result);
	}
}