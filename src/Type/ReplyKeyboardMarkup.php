<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;
use KeythKatz\TelegramBotCore\Exception\KeyboardSizeException;

class ReplyKeyboardMarkup extends BaseType
{
	protected static $map = [
		"keyboard" => true,
		"resize_keyboard" => false,
		"one_time_keyboard" => false,
		"selective" => false
	];

	/**
	 * Array of button rows, each represented by an Array of KeyboardButton objects
	 * @var array of array of KeyboardButton
	 */
	protected $keyboard;

	/**
	 * Optional. Requests clients to resize the keyboard vertically for optimal fit
	 * (e.g., make the keyboard smaller if there are just two rows of buttons).
	 * Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
	 * @var bool
	 */
	protected $resizeKeyboard = false;

	/**
	 * Optional. Requests clients to hide the keyboard as soon as it's been used.
	 * The keyboard will still be available, but clients will automatically display
	 * the usual letter-keyboard in the chat – the user can press a special button
	 * in the input field to see the custom keyboard again. Defaults to false.
	 * @var bool
	 */
	protected $oneTimeKeyboard = false;

	/**
	 * Optional. Use this parameter if you want to show the keyboard to specific users only.
	 * Targets: 1) users that are @mentioned in the text of the Message object;
	 * 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
	 * @var bool
	 */
	protected $selective = false;

	private $maxRow = -1;

	public function __construct(array $kb = [])
	{
		if (!empty($kb)) {
			$this->setKeyboard($kb);
		} else {
			$this->newKeyboard();
		}
	}

	/**
	 * Set the keyboard directly as an array.
	 * @param array $kb Array of array of KeyboardButton
	 */
	public function setKeyboard(array $kb): void
	{
		$this->keyboard = $kb;
	}

	public function setResizeKeyboard(bool $resize): void
	{
		$this->resizeKeyboard = $resize;
	}

	public function setOneTimeKeyboard(bool $otk): void
	{
		$this->oneTimeKeyboard = $otk;
	}

	public function selective(bool $s): void
	{
		$this->selective = $s;
	}

	public function addButton(KeyboardButton $button, int $row = -1): void
	{
		if ($this->maxRow <= $row && $row >= 0) {
			throw new KeyboardSizeException("There is no row $row");
		} else {
			if ($row < 0) {
				$row = $this->maxRow;
			}

			array_push($this->keyboard[$row], $button->toObject());
		}
	}

	public function addRow(): void
	{
		array_push($this->keyboard, []);
		$this->maxRow++;
	}

	private function newKeyboard(): void
	{
		$this->keyboard = [[]];
		$this->maxRow = 0;
	}
}