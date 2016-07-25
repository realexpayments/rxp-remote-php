<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * Class FraudFilterResul
 * Enumeration for the Fraud Filter Result.
 */
class FraudFilterResult extends EnumBase {

	const __default = self::PASS;
	const PASS = "PASS";
	const HOLD = "HOLD";
	const BLOCK = "BLOCK";
	const NOT_SUPPORTED = "NOT SUPPORTED";
	const NOT_EXECUTED = "NOT_EXECUTED";


	/**
	 * @var string The Fraud Result mode String value
	 */
	private $result;


	/**
	 * @param string $result
	 */
	public function __construct( $result ) {
		parent::__construct( $result );
		$this->result = $result;
	}

	/**
	 * Get the string value of the Fraud Filter Result
	 *
	 * @return string
	 */
	public function getMode() {
		return $this->result;
	}

}