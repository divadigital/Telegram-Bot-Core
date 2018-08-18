<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;

class InlineKeyboardButton extends BaseType
{
	protected static $map = [
		"text" => true,
		"url" => false,
		"callback_data" => false,
		"switch_inline_query" => false,
		"switch_inline_query_current_chat" => false,
		"callback_game" => false,
		"pay" => false
	];

	/**
	 * Label text on the button
	 * @var string
	 */
	protected $text;

	/**
	 * Optional. HTTP url to be opened when button is pressed
	 * @var string
	 */
	protected $url;

	/**
	 * Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
	 * @var string
	 */
	protected $callbackData;

	/**
	 * Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot‘s username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
	 * @var string
	 */
	protected $switchInlineQuery;

	/**
	 * Optional. If set, pressing the button will insert the bot‘s username and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username will be inserted.
	 * @var string
	 */
	protected $switchInlineQueryCurrentChat;

	/**
	 * Optional. Description of the game that will be launched when the user presses the button.
	 *
	 * NOTE: This type of button must always be the first button in the first row.
	 * @var string
	 */
	protected $callbackGame;

	/**
	 * Optional. Specify True, to send a Pay button.
	 *
	 * NOTE: This type of button must always be the first button in the first row.
	 * @var string
	 */
	protected $pay;

	public function __construct(string $text = "")
	{
		if ($text !== "") {
			$this->text = $text;
		}
	}

	public function setText(string $t): void
	{
		$this->text = $t;
	}

	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	public function setCallbackData($data): void
	{
		$this->callbackData = $data;
	}

	public function setSwitchInlineQuery(string $siq): void
	{
		$this->switchInlineQuery = $siq;
	}

	public function setSwitchInlineQueryCurrentChat(string $siq): void
	{
		$this->switchInlineQueryCurrentChat = $siq;
	}

	public function setCallbackGame(string $game): void
	{
		$this->callbackGame = $game;
	}

	public function setPay(bool $pay): void
	{
		$this->pay = $pay;
	}

}