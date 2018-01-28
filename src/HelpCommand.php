<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

class HelpCommand extends Command
{
	protected $name = "help";
	protected $helpText = "Show this message.";

	private $commands;

	public function __construct(array $commands)
	{
		$this->commands = $commands;
	}

	public function process($arguments, $message)
	{
		$reply = $this->sendMessageReply();

		$text = "Commands available for this bot:\r\n";
		foreach ($this->commands as $command) {
			if ($command->getHelpText() !== null) {
				$text .= "/" . $command->getName() . " - " . $command->getHelpText() . "\r\n";
			}
		}
		$text .= "/" . $this->getName() . " - " . $this->getHelpText() . "\r\n";

		$reply->setText($text);
		$reply->send();
	}
}