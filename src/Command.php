<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use KeythKatz\TelegramBotCore\Method\{
	SendMessage,
	SendAudio,
	SendPhoto,
	SendDocument,
	SendVideo,
	SendVoice
};

abstract class Command
{
	/**
	 * Name of the command, i.e. /[name]
	 * @var string
	 */
	protected $name = null;

	/**
	 * Text to show when a user types /help, unless overridden by another Command.
	 * Leave blank to not show in the list.
	 * @var string
	 */
	protected $helpText = "";

	/**
	 * Parent bot of the command. Set by the bot on command binding.
	 * @var TelegramBotCore
	 */
	protected $bot;

	/**
	 * Message that triggered this command.
	 * @var \TelegramBot\Api\Types\Message
	 */
	protected $message;

	/**
	 * What to do when the command is called.
	 * @param  string $arguments Arguments entered by the user.
	 * @param Message $message Message object that triggered this command.
	 */
	abstract public function process(string $arguments, \TelegramBot\Api\Types\Message $message);

	/**
	 * Set the bot that this command is linked to.
	 * @param TelegramBotCore $bot Bot that this command is linked to.
	 */
	public function setBot(TelegramBotCore $bot): void
	{
		$this->bot = $bot;
	}

	/**
	 * Set the message that triggered this command.
	 * @param \TelegramBot\Api\Types\Message $m The message that triggered this command.
	 */
	public function setMessage(\TelegramBot\Api\Types\Message $m): void
	{
		$this->message = $m;
	}

	/**
	 * Create a new blank message.
	 * @return SendMessage blank message.
	 */
	public function sendMessage(): SendMessage
	{
		return $this->bot->sendMessage();
	}

	/**
	 * Create a new blank message sending back to the chat
	 * @param  boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendMessage new message.
	 */
	public function sendMessageReply(bool $quoteOriginal = false): SendMessage
	{
		$m = $this->bot->sendMessage();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Forward the message that triggered this command.
	 * @param  int|string   $toChatId            Chat ID to forward to.
	 * @param  bool|boolean $disableNotification Disable notification for this message.
	 */
	public function forwardMessage($toChatId, bool $disableNotification = false): void
	{
		$m = $this->bot->forwardMessage();
		$m->setChatId($toChatId);
		$m->setFromChatId($this->message->getChat()->getId());
		$m->setMessageId($this->message->getMessageId());
		$m->setDisableNotification($disableNotification);

		$m->send();
	}

	/**
	 * Send a photo.
	 * @return SendPhoto new SendPhoto.
	 */
	public function sendPhoto(): SendPhoto
	{
		return $this->bot->sendPhoto();
	}

	/**
	 * Send a photo back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendPhoto                   New SendPhoto.
	 */
	public function sendPhotoReply(bool $quoteOriginal = false): SendPhoto
	{
		$m = $this->bot->sendPhoto();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a audio file.
	 * @return SendAudio new SendAudio.
	 */
	public function sendAudio(): SendAudio
	{
		return $this->bot->sendAudio();
	}

	/**
	 * Send a audio file back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendAudio                   New SendAudio.
	 */
	public function sendAudioReply(bool $quoteOriginal = false): SendAudio
	{
		$m = $this->bot->sendAudio();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a document.
	 * @return SendDocument new SendDocument.
	 */
	public function sendDocument(): SendDocument
	{
		return $this->bot->sendDocument();
	}

	/**
	 * Send a document back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendDocument                New SendDocument.
	 */
	public function sendDocumentReply(bool $quoteOriginal = false): SendDocument
	{
		$m = $this->bot->sendDocument();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a video.
	 * @return SendVideo new SendVideo.
	 */
	public function sendVideo(): SendVideo
	{
		return $this->bot->sendVideo();
	}

	/**
	 * Send a video back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendVideo                New SendVideo.
	 */
	public function sendVideoReply(bool $quoteOriginal = false): SendVideo
	{
		$m = $this->bot->sendVideo();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a voice message.
	 * @return SendVoice new SendVoice.
	 */
	public function sendVoice(): SendVoice
	{
		return $this->bot->sendVoice();
	}

	/**
	 * Send a voice message back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendVoice                New SendVoice.
	 */
	public function sendVoiceReply(bool $quoteOriginal = false): SendVoice
	{
		$m = $this->bot->sendVoice();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getHelpText(): string
	{
		return $this->helpText;
	}

	private function setReplyMarkup($m, bool $quoteOriginal): void
	{
		$m->setChatId($this->message->getChat()->getId());

		if ($quoteOriginal) {
			$m->setReplyToMessageId($this->message->getMessageId());
		}
	}
}