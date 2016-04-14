<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\EnumBase;

/**
 * Class ReasonCode
 * Enumeration for the Reason type.
 * @package com\realexpayments\remote\sdk\domain\payment
 */
class ReasonCode extends EnumBase {
	const __default = self::NOT_GIVEN;

	const FRAUD = "FRAUD";
	const OUT_OF_STOCK = "OUTOFSTOCK";
	const OTHER = "OTHER";
	const FALSE_POSITIVE = "FALSEPOSITIVE";
	const IN_STOCK = "INSTOCK";
	const NOT_GIVEN = "NOTGIVEN";
	
	/**
	 * @var string The payment type String value
	 */
	private $type;


	/**
	 * @param string $type
	 */
	public function __construct( $type ) {
		parent::__construct( $type );
		$this->type = $type;
	}

	/**
	 * Get the string value of the payment type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

}