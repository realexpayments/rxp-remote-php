<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * <p>
 * Class representing details of the card holder's bank (if available).
 * </p>
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 *
 */
class CardIssuer {

	/**
	 * @var string The Bank Name (e.g. First Data Bank).
	 *
	 */
	private $bank;

	/**
	 * @var string The Bank Country in English (e.g. UNITED STATES).
	 *
	 */
	private $country;

	/**
	 * @var string The country code of the issuing bank (e.g. US).
	 *
	 */
	private $countryCode;

	/**
	 * @var string The region the card was issued (e.g. US) Can be MEA (Middle East/Asia), LAT (Latin America), US (United States),
	 * EUR (Europe), CAN (Canada), A/P (Asia/Pacific).
	 *
	 */
	private $region;

	/**
	 * Getter for bank
	 *
	 * @return string
	 */
	public function getBank() {
		return $this->bank;
	}

	/**
	 * Setter for bank
	 *
	 * @param string $bank
	 */
	public function setBank( $bank ) {
		$this->bank = $bank;
	}

	/**
	 * Getter for country
	 *
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Setter for country
	 *
	 * @param string $country
	 */
	public function setCountry( $country ) {
		$this->country = $country;
	}

	/**
	 * Getter for countryCode
	 *
	 * @return string
	 */
	public function getCountryCode() {
		return $this->countryCode;
	}

	/**
	 * Setter for countryCode
	 *
	 * @param string $countryCode
	 */
	public function setCountryCode( $countryCode ) {
		$this->countryCode = $countryCode;
	}

	/**
	 * Getter for region
	 *
	 * @return string
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * Setter for region
	 *
	 * @param string $region
	 */
	public function setRegion( $region ) {
		$this->region = $region;
	}




}