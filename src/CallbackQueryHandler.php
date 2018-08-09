<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use TelegramBot\Api\Types\CallbackQuery;

abstract class CallbackQueryHandler extends BaseHandler
{	
	/**
	 * CallbackQuery that the triggered this handler.
	 * @var CallbackQuery
	 */
	protected $query;

	/**
	 * What to do when the bot receives a CallbackQuery.
	 * @param  CallbackQuery $query received CallbackQuery.
	 * @param  \TelegramBot\Api\Types\Message $message Message that the callback button originated from.
	 *                          May be null if the message is too old.
	 */
	abstract public function process(CallbackQuery $query, \TelegramBot\Api\Types\Message $message);

	/**
	 * Set the CallbackQuery that triggered this handler.
	 * @param CallbackQuery $q
	 */
	public function setQuery(CallbackQuery $q): void
	{
		$this->query = $q;
	}

	/**
	 * Answer the callback query.
	 * @return AnswerCallbackQuery new AnswerCallbackQuery.
	 */
	public function answerCallbackQuery(): AnswerCallbackQuery
	{
		$m = $this->bot->AnswerCallbackQuery();
		$m->setCallbackQueryId($this->query->getId());
		return $m;
	}
}