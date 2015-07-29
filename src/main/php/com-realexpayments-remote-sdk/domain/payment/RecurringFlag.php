<?php


namespace com\realexpayments\remote\sdk\domain\payment;
use com\realexpayments\remote\sdk\EnumBase;


/**
 * Enumeration representing the recurring flag.
 * @package com\realexpayments\remote\sdk\domain\payment
 */
class RecurringFlag extends EnumBase {

	const __default = self::NONE;

	const NONE = "";
	const ZERO = "0";
	const ONE = "1";
	const TWO = "2";

	/**
	 * @var string The flag value
	 */
	private $recurringFlag;


	/**
	 * Constructor for the enum
	 *
	 * @param string $recurringFlag
	 */
	public function __construct( $recurringFlag ) {
		parent::__construct( $recurringFlag );
		$this->recurringFlag = $recurringFlag;
	}

	/**
	 * Get the string value for the flag
	 *
	 * @return string
	 */
	public function getRecurringFlag() {
		return $this->recurringFlag;
	}

}