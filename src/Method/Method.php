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

		$promise = $curler->requestAsync("GET", static::$method, [
			"query" => $mappedParams
		]);

		$this->bot->addPromise($promise);
	}

	
}