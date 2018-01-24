<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore\Type;

use KeythKatz\TelegramBotCore\BaseType;

class KeyboardButton extends BaseType
{
	protected static $map = [
		"text" => true,
		"request_contact" => false,
		"request_location" => false
	];

	/**
	 * Text of the button. If none of the optional fields are used, it will be sent as a message when the button is pressed
	 * @var string
	 */
	protected $text;

	/**
	 * Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
	 * @var bool
	 */
	protected $requestContact = false;

	/**
	 * Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
	 * @var bool
	 */
	protected $requestLocation = false;

	public function __construct(string $text = "")
	{
		if ($text !== "") {
			$this->text = $text;
		}
	}

	public function setText(string $text): void
	{
		$this->text = $text;
	}

	public function setRequestContact(bool $rc): void
	{
		$this->requestContact = $rc;
	}

	public function setRequestLocation(bool $rl): void
	{
		$this->requestLocation = $rl;
	}

}