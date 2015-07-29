<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * Enum for recurring type. Type can be either fixed or variable depending on whether you will be changing
 * the amounts or not.
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 */
class RecurringType  extends EnumBase{

	const __default = self::NONE;

	const NONE = "";
	const VARIABLE = "variable";
	const FIXED = "fixed";

	/**
	 * @var string The type value
	 */
	private $type;


	/**
	 * Constructor for the enum
	 *
	 * @param string $type
	 */
	public function __construct($type)
	{
		parent::__construct($type);
		$this->type = $type;
	}

	/**
	 * Get the string value for the type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
}