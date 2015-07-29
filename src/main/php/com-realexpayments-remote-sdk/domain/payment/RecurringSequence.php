<?php


namespace com\realexpayments\remote\sdk\domain\payment;
use com\realexpayments\remote\sdk\EnumBase;


/**
 * Enumeration representing the recurring sequence. Must be first for the first transaction for this card,
 * subsequent for transactions after that, and last for the final transaction of the set.
 * Only supported by some acquirers.
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 */
class RecurringSequence extends EnumBase{

	const __default = self::NONE;

	const NONE = "";
	const FIRST = "first";
	const SUBSEQUENT = "subsequent";
	const LAST = "last";

	/**
	 * @var string The sequence value
	 */
	private $sequence;


	/**
	 * Constructor for the enum
	 *
	 * @param string $sequence
	 */
	public function __construct($sequence)
	{
		parent::__construct($sequence);
		$this->sequence= $sequence;
	}

	/**
	 * Get the string value for the sequence
	 *
	 * @return string
	 */
	public function getSequence() {
		return $this->sequence;
	}
}
