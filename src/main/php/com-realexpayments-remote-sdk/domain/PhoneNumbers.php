<?php


namespace com\realexpayments\remote\sdk\domain;

/**
 * Class PhoneNumbers
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing Payer phone numbers information to be passed to Realex.
 * </p>
 *
 * <p><code><pre>
 * $phoneNumbers = new PhoneNumbers();
 * $phoneNumbers->setHomePhoneNumber("+35317285355");
 * $phoneNumbers->setWorkPhoneNumber("+35317433923");
 * $phoneNumbers->setFaxPhoneNumber("+35317893248");
 * $phoneNumbers->setMobilePhoneNumber("+353873748392")
 *
 * </pre></code></p>
 *
 * @author vicpada
 */
class PhoneNumbers {

	/**
	 * @var string home phone number
	 */
	private $homePhoneNumber;

	/**
	 * @var string work phone number
	 */
	private $workPhoneNumber;

	/**
	 * @var string fax phone number
	 */
	private $faxPhoneNumber;

	/**
	 * @var string mobile phone number
	 */
	private $mobilePhoneNumber;

	/**
	 * PhoneNumbers constructor.
	 */
	public function __construct(  ) {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for homePhoneNumber
	 *
	 * @return string
	 */
	public function getHomePhoneNumber() {
		return $this->homePhoneNumber;
	}

	/**
	 * Setter for homePhoneNumber
	 *
	 * @param string $homePhoneNumber
	 */
	public function setHomePhoneNumber( $homePhoneNumber ) {
		$this->homePhoneNumber = $homePhoneNumber;
	}

	/**
	 * Getter for workPhoneNumber
	 *
	 * @return string
	 */
	public function getWorkPhoneNumber() {
		return $this->workPhoneNumber;
	}

	/**
	 * Setter for workPhoneNumber
	 *
	 * @param string $workPhoneNumber
	 */
	public function setWorkPhoneNumber( $workPhoneNumber ) {
		$this->workPhoneNumber = $workPhoneNumber;
	}

	/**
	 * Getter for faxPhoneNumber
	 *
	 * @return string
	 */
	public function getFaxPhoneNumber() {
		return $this->faxPhoneNumber;
	}

	/**
	 * Setter for faxPhoneNumber
	 *
	 * @param string $faxPhoneNumber
	 */
	public function setFaxPhoneNumber( $faxPhoneNumber ) {
		$this->faxPhoneNumber = $faxPhoneNumber;
	}

	/**
	 * Getter for mobilePhoneNumber
	 *
	 * @return string
	 */
	public function getMobilePhoneNumber() {
		return $this->mobilePhoneNumber;
	}

	/**
	 * Setter for mobilePhoneNumber
	 *
	 * @param string $mobilePhoneNumber
	 */
	public function setMobilePhoneNumber( $mobilePhoneNumber ) {
		$this->mobilePhoneNumber = $mobilePhoneNumber;
	}


}