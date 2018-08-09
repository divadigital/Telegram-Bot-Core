<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

abstract class Conversation
{
	protected $storageLocation;

	/**
	 * Bot that this conversation belongs to. Set by other classes.
	 * @var \KeythKatz\TelegramBotCore\TelegramBotCore
	 */
	protected $bot;

	protected $message;

	protected $chatId;

	protected $userId;

	private $stages = [];

	private $data = [];

	abstract public function initialize();

	public function setBot(\KeythKatz\TelegramBotCore\TelegramBotCore $bot): void
	{
		$this->bot = $bot;
	}

	public function setMessage(\TelegramBot\Api\Types\Message $message): void
	{
		$this->message = $message;
		$this->chatId = $this->message->getChat()->getId();
		$this->userId = $this->message->getFrom()->getId();
	}

	public function start(): void
	{
		$this->initialize();
		$this->startStage(array_keys($this->stages)[0]);
	}

	protected function addStage(string $name, string $message, callable $callback): void
	{
		$this->stages[$name] = ["message" => $message, "callback" => $callback];
	}

	private function startStage(string $name): void
	{
		$stage = $this->stages[$name];
		$text = $stage["message"];
		$message = $this->bot->sendMessage();
		$message->setChatId($this->chatId);
		$message->setReplyToMessageId($this->message->getMessageId());
		$message->setText($text);
		$message->setReplyMarkup(new \KeythKatz\TelegramBotCore\Type\ForceReply(true));
		$sentMessage = $message->send();
		$this->saveState($sentMessage->getMessageId(), $name);
	}

	private function saveState(int $sentMessageId, string $stageName): void
	{
		$fileName = $this->bot->getStorageLocation() . "/{$this->bot->getUsername()}_{$this->chatId}_{$this->userId}_{$sentMessageId}";
		$file = fopen($fileName, "w");
		fwrite($file, get_class($this) . "\r\n");
		fwrite($file, $stageName . "\r\n");
		fwrite($file, $this->chatId . "\r\n");
		fwrite($file, $this->userId . "\r\n");
		fwrite($file, serialize($this->data));
		fclose($file);
	}

	private function loadState($file): void
	{
		$this->initialize();
		$this->chatId = rtrim(fgets($file));
		$this->userId = rtrim(fgets($file));
		$this->data = unserialize(rtrim(fgets($file)));
	}

	private function callback(string $name, \TelegramBot\Api\Types\Message $message): void
	{
		$callback = $this->stages[$name]["callback"];
		$callback($message);
	}

	public static function resumeConversation(string $fileName, \TelegramBot\Api\Types\Message $message, \KeythKatz\TelegramBotCore\TelegramBotCore $bot): void
	{
		$file = fopen($fileName, "r");
		$className = rtrim(fgets($file));
		$stageName = rtrim(fgets($file));

		$conversation = new $className();
		$conversation->setBot($bot);
		$conversation->loadState($file);
		$conversation->callback($stageName, $message);

		fclose($file);
	}
}