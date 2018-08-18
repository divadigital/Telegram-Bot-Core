<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use TelegramBot\Api\Types\Message;
use KeythKatz\TelegramBotCore\Method\{
	Method,
	SendMessage,
	SendAudio,
	SendPhoto,
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
	AnswerCallbackQuery,
	EditMessageText,
	EditMessageCaption,
	EditMessageMedia,
	EditMessageReplyMarkup,
	DeleteMessage
};

abstract class BaseHandler
{	
	/**
	 * Parent bot of the handler. Set by the bot on handler binding.
	 * @var TelegramBotCore
	 */
	protected $bot;

	/**
	 * Message that the callback button originated from. May not be set.
	 * @var Message
	 */
	protected $message = null;

	/**
	 * Set the bot that this handler is linked to.
	 * @param TelegramBotCore $bot Bot that this handler is linked to.
	 */
	public function setBot(TelegramBotCore $bot): void
	{
		$this->bot = $bot;
	}

	/**
	 * Set the message that the callback button originated from.
	 * @param Message $m The message that the callback button originated from.
	 */
	public function setMessage(Message $m): void
	{
		$this->message = $m;
	}

	/**
	 * Start a new Conversation.
	 * @param  \KeythKatz\TelegramBotCore\Conversation $conversation instance of a conversation.
	 */
	protected function startConversation(\KeythKatz\TelegramBotCore\Conversation $conversation): void
	{
		$conversation->setBot($this->bot);
		$conversation->setMessage($this->message);
		$conversation->start();
	}

	protected function runCommand(\KeythKatz\TelegramBotCore\Command $command, string $arguments = ""): void
	{
		$command->setBot($this->bot);
		$command->setMessage($this->message);
		$command->process($arguments, $this->message);
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

	/**
	 * Send a video note.
	 * @return SendVideoNote new SendVideoNote.
	 */
	public function sendVideoNote(): SendVideoNote
	{
		return $this->bot->sendVideoNote();
	}

	/**
	 * Send a video note back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendVideoNote                New SendVideoNote.
	 */
	public function sendVideoNoteReply(bool $quoteOriginal = false): SendVideoNote
	{
		$m = $this->bot->sendVideoNote();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a media group.
	 * @return SendMediaGroup new SendMediaGroup.
	 */
	public function sendMediaGroup(): SendMediaGroup
	{
		return $this->bot->sendMediaGroup();
	}

	/**
	 * Send a media group back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendMediaGroup                New SendMediaGroup.
	 */
	public function sendMediaGroupReply(bool $quoteOriginal = false): SendMediaGroup
	{
		$m = $this->bot->sendMediaGroup();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a location.
	 * @return SendLocation new SendLocation.
	 */
	public function sendLocation(): SendLocation
	{
		return $this->bot->sendLocation();
	}

	/**
	 * Send a location back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendLocation                New SendLocation.
	 */
	public function sendLocationReply(bool $quoteOriginal = false): SendLocation
	{
		$m = $this->bot->sendLocation();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Edit a location.
	 * @return EditMessageLiveLocation new EditMessageLiveLocation.
	 */
	public function editMessageLiveLocation(): EditMessageLiveLocation
	{
		return $this->bot->editMessageLiveLocation();
	}

	/**
	 * Edit a location in the chat.
	 * @return EditMessageLiveLocation                New EditMessageLiveLocation.
	 */
	public function editMessageLiveLocationReply(): EditMessageLiveLocation
	{
		$m = $this->bot->editMessageLiveLocation();
		$this->setReplyMarkup($m, false);
		return $m;
	}

	/**
	 * Stop a live location.
	 * @return StopMessageLiveLocation new StopMessageLiveLocation.
	 */
	public function stopMessageLiveLocation(): StopMessageLiveLocation
	{
		return $this->bot->stopMessageLiveLocation();
	}

	/**
	 * Stop a live location in the chat.
	 * @return StopMessageLiveLocation                New StopMessageLiveLocation.
	 */
	public function stopMessageLiveLocationReply(): StopMessageLiveLocation
	{
		$m = $this->bot->stopMessageLiveLocation();
		$this->setReplyMarkup($m, false);
		return $m;
	}

	/**
	 * Send a venue.
	 * @return SendVenue new SendVenue.
	 */
	public function sendVenue(): SendVenue
	{
		return $this->bot->sendVenue();
	}

	/**
	 * Send a venue back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendVenue                New SendVenue.
	 */
	public function sendVenueReply(bool $quoteOriginal = false): SendVenue
	{
		$m = $this->bot->sendVenue();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a contact.
	 * @return SendContact new SendContact.
	 */
	public function sendContact(): SendContact
	{
		return $this->bot->sendContact();
	}

	/**
	 * Send a contact back to the chat.
	 * @param  bool|boolean $quoteOriginal If the original message should be quoted when replying. False by default.
	 * @return SendContact                New SendContact.
	 */
	public function sendContactReply(bool $quoteOriginal = false): SendContact
	{
		$m = $this->bot->sendContact();
		$this->setReplyMarkup($m, $quoteOriginal);
		return $m;
	}

	/**
	 * Send a chat action.
	 * @return SendChatAction new SendChatAction.
	 */
	public function sendChatAction(): SendChatAction
	{
		return $this->bot->sendChatAction();
	}

	/**
	 * Send a chat action back to the chat.
	 * @return SendChatAction                New SendChatAction.
	 */
	public function sendChatActionReply(): SendChatAction
	{
		$m = $this->bot->sendChatAction();
		$this->setReplyMarkup($m, false);
		return $m;
	}

	/**
	 * Answer a callback query.
	 * @return AnswerCallbackQuery new AnswerCallbackQuery.
	 */
	public function answerCallbackQuery(): AnswerCallbackQuery
	{
		return $this->bot->answerCallbackQuery();
	}

	/**
	 * Edit a message.
	 * @return EditMessageText new EditMessageText.
	 */
	public function editMessageText(): EditMessageText
	{
		return $this->bot->editMessageText();
	}

	/**
	 * Edit a message.
	 * @return EditMessageCaption new EditMessageCaption.
	 */
	public function editMessageCaption(): EditMessageCaption
	{
		return $this->bot->editMessageCaption();
	}

	/**
	 * Edit a message.
	 * @return EditMessageMedia new EditMessageMedia.
	 */
	public function editMessageMedia(): EditMessageMedia
	{
		return $this->bot->editMessageMedia();
	}

	/**
	 * Edit a message.
	 * @return EditMessageReplyMarkup new EditMessageReplyMarkup.
	 */
	public function editMessageReplyMarkup(): EditMessageReplyMarkup
	{
		return $this->bot->editMessageReplyMarkup();
	}

	/**
	 * Delete a message.
	 * @return DeleteMessage new DeleteMessage.
	 */
	public function deleteMessage(): DeleteMessage
	{
		return $this->bot->deleteMessage();
	}

	/**
	 * Get file information.
	 * @return GetFile new GetFile.
	 */
	public function getFile(): GetFile
	{
		return $this->bot->getFile();
	}

	private function setReplyMarkup(Method $m, bool $quoteOriginal): void
	{
		if ($this->message !== null) {
			$m->setChatId($this->message->getChat()->getId());
		}

		if ($quoteOriginal) {
			$m->setReplyToMessageId($this->message->getMessageId());
		}
	}
}