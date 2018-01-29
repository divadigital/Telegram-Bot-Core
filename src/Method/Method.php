<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\BaseType;

abstract class Method extends BaseType
{

	protected $tgUrl = "https://api.telegram.org/bot";

	/**
	 * Method in the Telegram API, e.g. "sendMessage".
	 * @var string
	 */
	protected static $method;

	protected $multipart = [];

	private $bot;

	public function __construct(string $token, \KeythKatz\TelegramBotCore\TelegramBotCore $bot)
	{
		$this->tgUrl .= $token . "/";
		$this->bot = $bot;
	}

	/**
	 * Map the set parameters and send it to Telegram.
	 */
	public function send(): void
	{
		$mappedParams = $this->map();

		$curler = new \GuzzleHttp\Client(['base_uri' => $this->tgUrl]);

		if (empty($this->multipart)) {
			$promise = $curler->requestAsync("POST", static::$method, [
				"query" => $mappedParams
			]);
		} else {
			$promise = $curler->requestAsync("POST", static::$method, [
				"query" => $mappedParams,
				"multipart" => $this->multipart
			]);
		}

		$this->bot->addPromise($promise);
	}

	protected function addMultipart(string $name, $resource)
	{
		array_push($this->multipart, ["name" => $name, "contents" => $resource]);
	}

	
}