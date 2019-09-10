<?php


namespace App\Domain;


class AssetId
{
	/** @var int */
	private $value;

	/**
	 * AssetId constructor.
	 * @param int $id
	 */
	public function __construct(int $value)
	{
		$this->value = $value;
	}

	public static function fromString($value)
	{
		return new self((int)$value);
	}

	public function __toString()
	{
		return $this->asString();
	}

	public function asString()
	{
		return (string) $this->value;
	}


}