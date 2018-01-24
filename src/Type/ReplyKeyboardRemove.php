<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;

class ReplyKeyboardRemove extends BaseType
{
	protected static $map = [
		"remove_keyboard" => true,
		"selective" => false
	];

	/**
	 * Requests clients to remove the custom keyboard (user will not be able to
	 * summon this keyboard; if you want to hide the keyboard from sight but keep
	 * it accessible, use one_time_keyboard in ReplyKeyboardMarkup)
	 * @var true
	 */
	protected $removeKeyboard = true;

	/**
	 * Optional. Use this parameter if you want to force reply from specific users only.
	 * Targets: 1) users that are @mentioned in the text of the Message object;
	 * 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
	 * @var bool
	 */
	protected $selective = false;

	public function __construct(bool $selective = false)
	{
		$this->removeKeyboard = true;
		$this->selective = $selective;
	}

	public function setSelective(bool $s): void
	{
		$this->selective = $s;
	}
}