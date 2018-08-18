<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

use KeythKatz\TelegramBotCore\BaseType;
use GuzzleHttp\Promise\Promise;
use TelegramBot\Api\Types\Message;

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
	 * @return Telegram's response.
	 */
	public function send()
	{
		$mappedParams = $this->map();

		$curler = new \GuzzleHttp\Client(['base_uri' => $this->tgUrl]);

		if (empty($this->multipart)) {
			$response = $curler->request("POST", static::$method, [
				"query" => $mappedParams
			]);
		} else {
			$response = $curler->request("POST", static::$method, [
				"query" => $mappedParams,
				"multipart" => $this->multipart
			]);
		}

		$jsonResponse = json_decode((string)$response->getBody(), true);
		$result = $jsonResponse['result'];
		return static::decodeResult($result);
	}

	/**
	 * Map the set parameters and send it to Telegram asynchronously.
	 */
	public function sendAsync(): Promise
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

		$processedPromise = $promise->then(function ($response) {
			$jsonResponse = json_decode((string)$response->getBody(), true);
			$result = $jsonResponse['result'];
			return static::decodeResult($result);
		});

		$this->bot->addPromise($processedPromise);
		return $processedPromise;
	}

	/**
	 * By default, most functions return a Message. This will be overridden
	 * for those that return other things.
	 * @param  array  $result parsed JSON that Telegram returns
	 * @return Message
	 */
	protected function decodeResult(array $result)
	{
		return Message::fromResponse($result);
	}

	protected function addMultipart(string $name, $resource)
	{
		array_push($this->multipart, ["name" => $name, "contents" => $resource]);
	}

	public function getBot(): \KeythKatz\TelegramBotCore\TelegramBotCore
	{
		return $this->bot;
	}
}