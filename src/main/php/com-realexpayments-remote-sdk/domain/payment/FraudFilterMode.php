<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * Class FraudFilterMode
 * Enumeration for the Fraud Filter mode.
 */
class FraudFilterMode extends EnumBase {

	const __default = self::OFF;
	const ACTIVE = "ACTIVE";
	const PASSIVE = "PASSIVE";
	const OFF = "OFF";
	const ERROR = "ERROR";


	/**
	 * @var string The Fraud Filter mode String value
	 */
	private $mode;


	/**
	 * @param string $mode
	 */
	public function __construct( $mode ) {
		parent::__construct( $mode );
		$this->mode = $mode;
	}

	/**
	 * Get the string value of the Fraud Filter mode
	 *
	 * @return string
	 */
	public function getMode() {
		return $this->mode;
	}

}