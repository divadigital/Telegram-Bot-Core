<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

class InputFile
{
	protected $file;

	public function __construct($resource)
	{
		$this->file = $resource;
	}

	public function getFile()
	{
		return $this->file;
	}
}