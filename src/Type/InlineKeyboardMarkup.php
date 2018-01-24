<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;
use KeythKatz\TelegramBotCore\Exception\KeyboardSizeException;

class InlineKeyboardMarkup extends BaseType
{
	protected static $map = [
		"inline_keyboard" => true
	];

	/**
	 * Array of button rows, each represented by an Array of InlineKeyboardButton objects
	 * @var array of array of InlineKeyboardButton
	 */
	protected $inlineKeyboard;

	private $maxRow = -1;

	public function __construct(array $kb = [])
	{
		if (!empty($kb)) {
			$this->setInlineKeyboard($kb);
		} else {
			$this->newKeyboard();
		}
	}

	/**
	 * Set the keyboard directly as an array.
	 * @param array $kb Array of array of InlineKeyboardButton
	 */
	public function setInlineKeyboard(array $kb): void
	{
		$this->inlineKeyboard = $kb;
	}

	public function addButton(InlineKeyboardButton $button, int $row = -1): void
	{
		if ($this->maxRow <= $row && $row >= 0) {
			throw new KeyboardSizeException("There is no row $row");
		} else {
			if ($row < 0) {
				$row = $this->maxRow;
			}

			array_push($this->inlineKeyboard[$row], $button->toObject());
		}
	}

	public function addRow(): void
	{
		array_push($this->inlineKeyboard, []);
		$this->maxRow++;
	}

	private function newKeyboard(): void
	{
		$this->inlineKeyboard = [[]];
		$this->maxRow = 0;
	}
}