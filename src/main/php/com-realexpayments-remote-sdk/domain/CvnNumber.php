<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Class CvnNumber
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing PaymentData CVN number information to be passed to
 * Realex Card Storage for Receipt-in transactions.
 * Contains the CVN number for the stored card
 * </p>
 *
 * <p><code><pre>
 *
 * $cvnNumber = new (CvnNumber())
 *      ->addNumber("123");
 *
 * </pre></code></p>
 *
 * @author vicpada
 */
class CvnNumber {

	/**
	 * @var string A three-digit number on the reverse of the card. It is called the
	 * CVC for VISA and the CVV2 for MasterCard. For an AMEX card, it is a four digit number.
	 */
	private $number;

	/**
	 * CvnNumber constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for number
	 *
	 * @return string
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * Setter for number
	 *
	 * @param string $number
	 */
	public function setNumber( $number ) {
		$this->number = $number;
	}

	/**
	 * Helper method for adding a number
	 *
	 * @param string $number
	 *
	 * @return CvnNumber
	 */
	public function addNumber( $number ) {
		$this->number = $number;

		return $this;
	}


}