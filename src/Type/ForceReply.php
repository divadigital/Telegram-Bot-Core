<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;

class ForceReply extends BaseType
{
	protected static $map = [
		"force_reply" => true,
		"selective" => false
	];

	/**
	 * Shows reply interface to the user, as if they manually selected the bot‘s message and tapped ’Reply'
	 * @var true
	 */
	protected $forceReply = true;

	/**
	 * Optional. Use this parameter if you want to force reply from specific users only.
	 * Targets: 1) users that are @mentioned in the text of the Message object;
	 * 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
	 * @var bool
	 */
	protected $selective = false;

	public function __construct(bool $selective = false)
	{
		$this->forceReply = true;
		$this->selective = $selective;
	}

	public function setSelective(bool $s): void
	{
		$this->selective = $s;
	}
}