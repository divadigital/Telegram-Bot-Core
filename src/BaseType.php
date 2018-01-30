<?php
declare(strict_types=1);

namespace KeythKatz\TelegramBotCore;

use KeythKatz\TelegramBotCore\Exception\RequiredParamException;

abstract class BaseType
{

	/**
	 * Map of parameters for the type in the Telegram API to it's mandatoriness.
	 * @var array(string => bool)
	 */
	protected static $map;

	protected function map(): array
	{
		$mappedParams = [];
		foreach (static::$map as $param => $required) {
			$camelCasedParam = self::toCamelCase(($param));

			if ($required && $this->$camelCasedParam === null) {
				throw new RequiredParamException("Required parameters were not set.");
			} else {
				if ($this->$camelCasedParam instanceof self) {
					$mappedParams[$param] = $this->$camelCasedParam->toJson();
				} else if ($this->$camelCasedParam !== null){
					$mappedParams[$param] = $this->$camelCasedParam;
				}
			}
		}

		return $mappedParams;
	}

	protected static function toCamelCase(string $str): string
	{
		return lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $str))));
	}

	public function toJson(): string
	{
		return json_encode($this->map());
	}

	public function toObject(): object
	{
		return json_decode(json_encode($this->map()));
	}
}