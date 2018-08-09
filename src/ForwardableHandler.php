<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use KeythKatz\TelegramBotCore\Method\{
	ForwardMessage
};

abstract class ForwardableHandler extends BaseHandler
{
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
}