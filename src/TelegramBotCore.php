<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Exception\ClientException;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\BotApi;
use KeythKatz\TelegramBotCore\Method\{
	SendMessage,
	ForwardMessage,
	SendPhoto,
	SendAudio,
	SendDocument,
	SendVideo,
	SendVoice,
	SendVideoNote,
	SendMediaGroup,
	SendLocation,
	EditMessageLiveLocation,
	StopMessageLiveLocation,
	SendVenue,
	SendContact,
	SendChatAction,
	GetFile,
	AnswerCallbackQuery
};

//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;

abstract class TelegramBotCore
{
	/**
	 * Token given by telegram for the bot.
	 * @var string
	 */
	protected $token = null;

	/**
	 * Username of the bot, e.g. @exampleBot
	 * @var string
	 */
	protected $username = null;

	/**
	 * Directory to store conversation files
	 * @var string
	 */
	protected $storageLocation = __DIR__;

	private $commands = [];
	private $promises = [];
	private $cqHandler = null;
	private $genericMessageHandler = null;
	private $bannedUsers = [];

	public function __construct(string $username, string $token, string $storageLocation = __DIR__) {
		$this->username = $username;
		$this->token = $token;
		$this->storageLocation = $storageLocation;
	}

	/**
	 * Add all your commands and handlers within this function.
	 */
	abstract protected function registerHandlers();

	/**
	 * Call this function to turn on the bot when processing via webhooks.
	 */
	public function webhook(): void
	{
		//$log = new Logger('botlog');
		//$log->pushHandler(new StreamHandler(__DIR__ . "/../log/botlog.log", Logger::DEBUG));

		if ($data = BotApi::jsonValidate(file_get_contents('php://input'), true)) {
			//$log->debug(file_get_contents('php://input'));
			$update = Update::fromResponse($data);
			$this->processUpdate($update);
		}
	}

	/**
	 * Create a new SendMessage.
	 * @return SendMessage new blank SendMessage.
	 */
	public function sendMessage(): SendMessage
	{
		return new SendMessage($this->token, $this);
	}

	/**
	 * Create a new ForwardMessage.
	 * @return ForwardMessage new blank ForwardMessage.
	 */
	public function forwardMessage(): ForwardMessage
	{
		return new ForwardMessage($this->token, $this);
	}

	/**
	 * Create a new SendPhoto.
	 * @return SendPhoto new blank SendPhoto.
	 */
	public function sendPhoto(): SendPhoto
	{
		return new SendPhoto($this->token, $this);
	}

	/**
	 * Create a new SendDocument.
	 * @return SendDocument new blank SendDocument.
	 */
	public function sendDocument(): SendDocument
	{
		return new SendDocument($this->token, $this);
	}

	/**
	 * Create a new SendAudio.
	 * @return SendAudio new blank SendAudio.
	 */
	public function sendAudio(): SendAudio
	{
		return new SendAudio($this->token, $this);
	}

	/**
	 * Create a new SendVideo.
	 * @return SendVideo new blank SendVideo.
	 */
	public function sendVideo(): SendVideo
	{
		return new SendVideo($this->token, $this);
	}

	/**
	 * Create a new SendVoice.
	 * @return SendVoice new blank SendVoice.
	 */
	public function sendVoice(): SendVoice
	{
		return new SendVoice($this->token, $this);
	}

	/**
	 * Create a new SendVideoNote.
	 * @return SendVideoNote new blank SendVideoNote.
	 */
	public function sendVideoNote(): SendVideoNote
	{
		return new SendVideoNote($this->token, $this);
	}

	/**
	 * Create a new SendMediaGroup.
	 * @return SendMediaGroup new blank SendMediaGroup.
	 */
	public function sendMediaGroup(): SendMediaGroup
	{
		return new SendMediaGroup($this->token, $this);
	}

	/**
	 * Create a new SendLocation.
	 * @return SendLocation new blank SendLocation.
	 */
	public function sendLocation(): SendLocation
	{
		return new SendLocation($this->token, $this);
	}

	/**
	 * Create a new EditMessageLiveLocation.
	 * @return EditMessageLiveLocation new blank EditMessageLiveLocation.
	 */
	public function editMessageLiveLocation(): EditMessageLiveLocation
	{
		return new EditMessageLiveLocation($this->token, $this);
	}

	/**
	 * Create a new StopMessageLiveLocation.
	 * @return StopMessageLiveLocation new blank StopMessageLiveLocation.
	 */
	public function stopMessageLiveLocation(): StopMessageLiveLocation
	{
		return new StopMessageLiveLocation($this->token, $this);
	}

	/**
	 * Create a new SendVenue.
	 * @return SendVenue new blank SendVenue.
	 */
	public function sendVenue(): SendVenue
	{
		return new SendVenue($this->token, $this);
	}

	/**
	 * Create a new SendContact.
	 * @return SendContact new blank SendContact.
	 */
	public function sendContact(): SendContact
	{
		return new SendContact($this->token, $this);
	}

	/**
	 * Create a new SendChatAction.
	 * @return SendChatAction new blank SendChatAction.
	 */
	public function sendChatAction(): SendChatAction
	{
		return new SendChatAction($this->token, $this);
	}

	/**
	 * Create a new GetFile.
	 * @return GetFile new blank GetFile.
	 */
	public function getFile(): GetFile
	{
		return new GetFile($this->token, $this);
	}

	/**
	 * Create a new AnswerCallbackQuery.
	 * @return AnswerCallbackQuery new blank AnswerCallbackQuery.
	 */
	public function answerCallbackQuery(): AnswerCallbackQuery
	{
		return new AnswerCallbackQuery($this->token, $this);
	}

	/**
	 * Add a command to the list of commands.
	 * @param Command $handler Handler for the command.
	 */
	protected function addCommand(Command $handler): void
	{
		$handler->setBot($this);
		$command = $handler->getName();
		$this->commands[strtoupper($command)] = $handler;
	}
	
	/**
	 * Set the handler to handle callback queries.
	 * @param CallbackQueryHandler $handler CallbackQuery handler.
	 */
	protected function setCallbackQueryHandler(CallbackQueryHandler $handler): void
	{
		$handler->setBot($this);
		$this->cqHandler = $handler;
	}

	/**
	 * Set the handler to handle plaintext messages.
	 * @param PlaintextHandler $handler Plaintext handler.
	 */
	protected function setGenericMessageHandler(GenericMessageHandler $handler): void
	{
		$handler->setBot($this);
		$this->genericMessageHandler = $handler;
	}

	/**
	 * Add a user whose messages will not be banned.
	 * The users will be alerted when they attempt to interact with the bot.
	 * @param int $id User ID to ban.
	 */
	protected function addBannedUser(int $id): void
	{
		array_push($this->bannedUsers, $id);
	}

	/**
	 * Process the formatted update.
	 * @param  Update $update
	 */
	protected function processUpdate(Update $update): void
	{
		// Register all commands
		$this->registerHandlers();
		$this->registerHelpCommand();

		// messages
		if ($update->getMessage() !== null) {

			$message = $update->getMessage();

			// Banned users
			$senderId = $message->getFrom()->getId();
			if ($this->userIsBanned($senderId)) {
				$this->replyBannedUser($message->getChat()->getId(), $senderId);
				goto postProcessing;
			}

			// Check for conversations
			if ($message->getReplyToMessage() !== null) {
				$repliedMessage = $message->getReplyToMessage();
				$repliedMessageId = $repliedMessage->getMessageId();
				$chatId = $message->getChat()->getId();
				$userId = $message->getFrom()->getId();
				$botName = $this->username;
				$fileName = $this->storageLocation . "/{$botName}_{$chatId}_{$userId}_{$repliedMessageId}";

				if (file_exists($fileName)) {
					\KeythKatz\TelegramBotCore\Conversation::resumeConversation($fileName, $message, $this);
				}
				goto postProcessing;
			}

			// Parse for valid targeted command
			$rawMessage = $message->getText();
			if ($rawMessage !== null && substr($rawMessage, 0, 1) === '/') {

				list($command, $arguments) = $this->splitMessage($rawMessage);

				// Call handler
				if (isset($this->commands[$command])) {
					$handler = $this->commands[$command];
					$handler->setMessage($message);
					$handler->process($arguments, $message);
				}
			// Raw Message
			} else {
				$this->genericMessageHandler->setMessage($message);
				$this->genericMessageHandler->process($message);
			}
		// Callback queries
		} else if ($update->getCallbackQuery() !== null) {
			$query = $update->getCallbackQuery();

			// Check for InteractiveMessage
			$message = $query->getMessage();
			if ($message != null) {
				$botName = $this->username;
				$chatId = $message->getChat()->getId();
				$messageId = $message->getMessageId();
				$fileName = $this->storageLocation . "/{$botName}_{$chatId}_interactive_{$messageId}";
				if (file_exists($fileName)) {
					\KeythKatz\TelegramBotCore\InteractiveMessage::handleCallbackQuery($fileName, $query, $this);
				}
			// Default callback query
			} else if ($this->cqHandler !== null) {

				$senderId = $query->getFrom()->getId();
				if ($this->userIsBanned($senderId)) {
					$this->replyBannedUser($query->getMessage()->getChat()->getId(), $senderId);
					goto postProcessing;
				}

				$message = $query->getMessage();
				if ($message !== null) {
					$this->cqHandler->setMessage($message);
				}

				$this->cqHandler->setQuery($query);
				$this->cqHandler->process($query, $message);
			}
		}

postProcessing:
		$this->finishPromises();
		echo "Everything will be okay";
	}

	private function userIsBanned(int $id): bool
	{
		return in_array($id, $this->bannedUsers);
	}

	private function replyBannedUser($chatId, int $userId): void
	{
		$reply = $this->sendMessage();
		$reply->setChatId($chatId);
		$reply->setText("You are currently banned.");
		$reply->send();
	}

	private function registerHelpCommand(): void
	{
		if (!isset($this->commands["HELP"])) {
			$this->addCommand(new \KeythKatz\TelegramBotCore\HelpCommand($this->commands));
		}
	}

	public function addPromise(Promise $promise): void
	{
		array_push($this->promises, $promise);
	}

	private function finishPromises(): void
	{
		foreach ($this->promises as $promise) {
			try {
				$promise->wait();
			} catch (ClientException $e) {
				echo "<pre>" . $e->getMessage() . "\r\n</pre>";
			}
		}
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getStorageLocation(): string
	{
		return $this->storageLocation;
	}

	private function splitMessage(string $rawMessage): array
	{
		// Extract command
		$firstSpace = stripos($rawMessage, ' ');
		if ($firstSpace === false) {
			$rawCommand = substr($rawMessage, 1);
		} else {
			$rawCommand = substr($rawMessage, 1, $firstSpace - 1);
		}

		// Remove own username from back of command if found
		if (($usernamePos = stripos($rawCommand, $this->username)) === false) {
			$command = strtoupper($rawCommand);
		} else {
			$command = strtoupper(substr($rawCommand, 0, $usernamePos));
		}

		// Extract arguments
		if ($firstSpace !== false) {
			$arguments = substr($rawMessage, $firstSpace + 1);
		} else {
			$arguments = null;
		}

		return [$command, $arguments];
	}
}