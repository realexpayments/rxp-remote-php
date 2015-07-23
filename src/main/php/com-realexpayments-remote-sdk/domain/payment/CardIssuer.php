<?php


namespace com\realexpayments\remote\sdk\domain\payment;
use Doctrine\OXM\Mapping as DOM;


/**
 * <p>
 * Class representing details of the card holder's bank (if available).
 * </p>
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 *
 * @Dom\XmlEntity(xml="cardissuer")
 */
class CardIssuer {

	/**
	 * @var string The Bank Name (e.g. First Data Bank).
	 *
	 * @Dom\XmlText(type="string",name="bank")
	 */
	private $bank;

	/**
	 * @var string The Bank Country in English (e.g. UNITED STATES).
	 *
	 * @Dom\XmlText(type="string",name="country")
	 */
	private $country;

	/**
	 * @var string The country code of the issuing bank (e.g. US).
	 *
	 * @Dom\XmlText(type="string",name="countrycode")
	 */
	private $countryCode;

	/**
	 * @var string The region the card was issued (e.g. US) Can be MEA (Middle East/Asia), LAT (Latin America), US (United States),
	 * EUR (Europe), CAN (Canada), A/P (Asia/Pacific).
	 *
	 * @Dom\XmlText(type="string",name="region")
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