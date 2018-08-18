<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

abstract class Conversation extends ForwardableHandler
{
	/**
	 * Chat ID that the conversation belongs to.
	 * @var int
	 */
	protected $chatId;

	/**
	 * ID of the user that initiated the conversation.
	 * @var int
	 */
	protected $userId;

	/**
	 * Name of the current stage of conversation.
	 * @var string
	 */
	private $currentStage;

	/**
	 * Stores stage data as an array - "message" to send at the start of the stage
	 * and the "callback" after receiving a reply.
	 * @var array
	 */
	private $stages = [];

	/**
	 * Data that the user can store.
	 * @var array
	 */
	private $data = [];

	/**
	 * Initialise stages here by calling $this->addStage() for each stage.
	 */
	abstract public function initialize();

	/**
	 * Add a stage.
	 * @param string   $name     Name of the stage.
	 * @param string   $message  Message to send and ask a reply to.
	 * @param callable $callback Callback that is called with the replying message as a parameter.
	 */
	protected function addStage(string $name, string $message, callable $callback): void
	{
		$this->stages[$name] = ["message" => $message, "callback" => $callback];
	}

	/**
	 * Save data that will be persistent across messages.
	 * @param  string $name Name of the data.
	 * @param  anything $data Data to be stored.
	 */
	protected function saveData(string $name, $data): void
	{
		$this->data[$name] = $data;
	}

	/**
	 * Load saved data.
	 * @param  string $name Name of the data.
	 * @return anything       Saved data.
	 */
	protected function loadData(string $name)
	{
		if (isset($this->data[$name])) return $this->data[$name];
		else return null;
	}

	/**
	 * Repeat the current stage, for example on invalid input.
	 */
	protected function repeatStage(): void
	{
		$this->startStage($this->currentStage);
	}

	/**
	 * Set the current stage, i.e. move on in the conversation.
	 * @param string $name Name of the stage to move to.
	 */
	protected function setStage(string $name): void
	{
		$this->startStage($name);
	}

	/**
	 * Sets the message that last affected the conversation. Done by the framework.
	 * @param \TelegramBot\Api\Types\Message $message
	 */
	public function setMessage(\TelegramBot\Api\Types\Message $message): void
	{
		$this->message = $message;
		$this->chatId = $this->message->getChat()->getId();
		$this->userId = $this->message->getFrom()->getId();
	}

	/**
	 * Start the conversation from the first added stage.
	 */
	public function start(): void
	{
		$this->initialize();
		$this->startStage(array_keys($this->stages)[0]);
	}

	private function startStage(string $name): void
	{
		$this->currentStage = $name;
		$stage = $this->stages[$name];
		$text = $stage["message"];
		$message = $this->sendMessageReply();
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

	private function callback(\TelegramBot\Api\Types\Message $message): void
	{
		$callback = $this->stages[$this->currentStage]["callback"];
		$callback($message);
	}

	public static function resumeConversation(string $fileName, \TelegramBot\Api\Types\Message $message, \KeythKatz\TelegramBotCore\TelegramBotCore $bot): void
	{
		$file = fopen($fileName, "r");
		$className = rtrim(fgets($file));
		$stageName = rtrim(fgets($file));

		$conversation = new $className();
		$conversation->setBot($bot);
		$conversation->setMessage($message);
		$conversation->loadState($file);
		$conversation->currentStage = $stageName;
		$conversation->callback($message);

		fclose($file);
		unlink($fileName);
	}
}