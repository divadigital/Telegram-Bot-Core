<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Method;

class AnswerCallbackQuery extends Method
{

	protected static $method = "answerCallbackQuery";

	protected static $map = [
		"callback_query_id" => true,
		"text" => false,
		"show_alert" => false,
		"url" => false,
		"cache_time" => false
	];

	/**
	 * Unique identifier for the query to be answered
	 * @var string
	 */
	protected $callbackQueryId;

	/**
	 * Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters
	 * @var string
	 */
	protected $text;

	/**
	 * If true, an alert will be shown by the client instead of a notification
	 * at the top of the chat screen. Defaults to false.
	 * @var bool
	 */
	protected $showAlert;

	/**
	 * URL that will be opened by the user's client. If you have created a Game
	 * and accepted the conditions via @Botfather, specify the URL that opens
	 * your game – note that this will only work if the query comes from a
	 * callback_game button.
	 * @var string
	 */
	protected $url;

	/**
	 * The maximum amount of time in seconds that the result of the callback
	 * query may be cached client-side. Telegram apps will support caching
	 * starting in version 3.14. Defaults to 0.
	 * @var int
	 */
	protected $cacheTime;

	protected function decodeResult($result)
	{
		return $result;
	}

	public function setCallbackQueryId(string $id)
	{
		$this->callbackQueryId = $id;
	}

	public function setText(string $text)
	{
		$this->text = $text;
	}

	public function setShowAlert(bool $showAlert)
	{
		$this->showAlert = $showAlert;
	}

	public function setUrl(string $url)
	{
		$this->url = $url;
	}

	public function setCacheTime(int $cacheTime)
	{
		$this->cacheTime = $cacheTime;
	}
}
