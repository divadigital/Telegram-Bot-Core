<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

abstract class InteractiveMessage extends BaseHandler
{
	/**
	 * Method that will be sent.
	 * @var \KeythKatz\TelegramBotCore\Method\Method
	 */
	private $baseMethod;

	/**
	 * Data that the user can store.
	 * @var array
	 */
	private $data = [];

	/**
	 * The callback query that triggered this handler.
	 * @var \TelegramBot\Api\Types\CallbackQuery
	 */
	protected $callbackQuery;

	protected $chatId;

	protected $messageId;

	/**
	 * @param \KeythKatz\TelegramBotCore\Method\Method $baseMethod Method that will be sent. Must be able to setReplyMarkup on it.
	 */
	public function __construct(\KeythKatz\TelegramBotCore\Method\Method $baseMethod = null)
	{
		if ($baseMethod !== null) {
			$this->baseMethod = $baseMethod;
			$this->bot = $baseMethod->getBot();
		}
	}

	/**
	 * What to do when a CallbackQuery belonging to this message is received.
	 * @param  \TelegramBot\Api\Types\CallbackQuery $callbackQuery CallbackQuery received.
	 * @param  \TelegramBot\Api\Types\Message       $message       Message attached to the CallbackQuery
	 */
	abstract public function process(\TelegramBot\Api\Types\CallbackQuery $callbackQuery, \TelegramBot\Api\Types\Message $message);

	/**
	 * Set the inline keyboard for the message.
	 * @param \KeythKatz\TelegramBotCore\Type\InlineKeyboardMarkup $keyboard InlineKeyboardMarkup to send with the message.
	 */
	public function setReplyMarkup(\KeythKatz\TelegramBotCore\Type\InlineKeyboardMarkup $keyboard): void
	{
		$this->baseMethod->setReplyMarkup($keyboard);
	}

	/**
	 * Send the message. The InteractiveMessage and its data will then be saved locally.
	 */
	public function send(): void
	{	
		$sentMessage = $this->baseMethod->send();
		$this->messageId = $sentMessage->getMessageId();
		$this->chatId = $sentMessage->getChat()->getId();

		$this->saveState();
	}

	/**
	 * Save data that will be persistent.
	 * @param  string $name Name of the data.
	 * @param  anything $data Data to be stored.
	 */
	public function saveData(string $name, $data): void
	{
		$this->data[$name] = $data;
	}

	/**
	 * Load saved data.
	 * @param  string $name Name of the data.
	 * @return anything       Saved data.
	 */
	public function loadData(string $name)
	{
		if (isset($this->data[$name])) return $this->data[$name];
		else return null;
	}

	public function editMessageText(): \KeythKatz\TelegramBotCore\Method\EditMessageText
	{
		$m = $this->bot->editMessageText();
		$m->setChatId($this->chatId);
		$m->setMessageId($this->messageId);
		return $m;
	}

	private function saveState(): void
	{
		$fileName = $this->bot->getStorageLocation() . "/{$this->bot->getUsername()}_{$this->chatId}_interactive_{$this->messageId}";
		$file = fopen($fileName, "w");
		fwrite($file, get_class($this) . "\r\n");
		fwrite($file, serialize($this->chatId) . "\r\n");
		fwrite($file, serialize($this->messageId) . "\r\n");
		fwrite($file, serialize($this->data));
		fclose($file);
	}

	private function loadState($file): void
	{
		$this->chatId = unserialize(rtrim(fgets($file)));
		$this->messageId = unserialize(rtrim(fgets($file)));
		$this->data = unserialize(rtrim(fgets($file)));
	}

	public static function handleCallbackQuery(string $fileName, \TelegramBot\Api\Types\CallbackQuery $callbackQuery, \KeythKatz\TelegramBotCore\TelegramBotCore $bot): void
	{
		$file = fopen($fileName, "r");
		$className = rtrim(fgets($file));

		$handler = new $className();
		$handler->setBot($bot);
		$handler->callbackQuery = $callbackQuery;
		$handler->message = $callbackQuery->getMessage();
		$handler->loadState($file);
		$handler->process($callbackQuery, $callbackQuery->getMessage());

		fclose($file);
		$handler->saveState();
	}
}