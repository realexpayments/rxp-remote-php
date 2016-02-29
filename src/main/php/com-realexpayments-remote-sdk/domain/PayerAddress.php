<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Class PayerAddress
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing Payer information to be passed to Realex.
 * </p>
 *
 * <p><code><pre>
 *
 * $address = new ( PayerAddress() )
 * ->addLine1("Apt 167 Block 10")
 * ->addLine2("The Hills")
 * ->addLine3("67-69 High St")
 * ->addCity("Hytown")
 * ->addCounty("Dunham")
 * ->addPostCode("3")
 * ->addCountryCode("IE")
 * ->addCountryName("Ireland");
 *
 * </pre></code></p>
 *
 * @author vicpada
 */
class PayerAddress {

	/**
	 * @var string line 1
	 */
	private $line1;

	/**
	 * @var string line 2
	 */
	private $line2;

	/**
	 * @var string line 3
	 */
	private $line3;

	/**
	 * @var string city
	 */
	private $city;

	/**
	 * @var string county
	 */
	private $county;

	/**
	 * @var string postcode
	 */
	private $postcode;

	/**
	 * @var     Country country
	 */
	private $country;

	/**
	 * PayerAddress constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for line1
	 *
	 * @return string
	 */
	public function getLine1() {
		return $this->line1;
	}

	/**
	 * Setter for line1
	 *
	 * @param string $line1
	 */
	public function setLine1( $line1 ) {
		$this->line1 = $line1;
	}

	/**
	 * Getter for line2
	 *
	 * @return string
	 */
	public function getLine2() {
		return $this->line2;
	}

	/**
	 * Setter for line2
	 *
	 * @param string $line2
	 */
	public function setLine2( $line2 ) {
		$this->line2 = $line2;
	}

	/**
	 * Getter for line3
	 *
	 * @return string
	 */
	public function getLine3() {
		return $this->line3;
	}

	/**
	 * Setter for line3
	 *
	 * @param string $line3
	 */
	public function setLine3( $line3 ) {
		$this->line3 = $line3;
	}

	/**
	 * Getter for city
	 *
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Setter for city
	 *
	 * @param string $city
	 */
	public function setCity( $city ) {
		$this->city = $city;
	}

	/**
	 * Getter for county
	 *
	 * @return string
	 */
	public function getCounty() {
		return $this->county;
	}

	/**
	 * Setter for county
	 *
	 * @param string $county
	 */
	public function setCounty( $county ) {
		$this->county = $county;
	}

	/**
	 * Getter for postcode
	 *
	 * @return string
	 */
	public function getPostcode() {
		return $this->postcode;
	}

	/**
	 * Setter for postcode
	 *
	 * @param string $postcode
	 */
	public function setPostcode( $postcode ) {
		$this->postcode = $postcode;
	}

	/**
	 * Getter for country
	 *
	 * @return Country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Setter for country
	 *
	 * @param Country $country
	 */
	public function setCountry( $country ) {
		$this->country = $country;
	}


	/**
	 * Helper method for adding a line1
	 *
	 * @param string $line1
	 *
	 * @return PayerAddress
	 */
	public function addLine1( $line1 ) {
		$this->line1 = $line1;

		return $this;
	}

	/**
	 * Helper method for adding a line2
	 *
	 * @param string $line2
	 *
	 * @return PayerAddress
	 */
	public function addLine2( $line2 ) {
		$this->line2 = $line2;

		return $this;
	}

	/**
	 * Helper method for adding a line3
	 *
	 * @param string $line3
	 *
	 * @return PayerAddress
	 */
	public function addLine3( $line3 ) {
		$this->line3 = $line3;

		return $this;
	}

	/**
	 * Helper method for adding a city
	 *
	 * @param string $city
	 *
	 * @return PayerAddress
	 */
	public function addCity( $city ) {
		$this->city = $city;

		return $this;
	}

	/**
	 * Helper method for adding a county
	 *
	 * @param string $county
	 *
	 * @return PayerAddress
	 */
	public function addCounty( $county ) {
		$this->county = $county;

		return $this;
	}

	/**
	 * Helper method for adding a postcode
	 *
	 * @param string $postcode
	 *
	 * @return PayerAddress
	 */
	public function addPostcode( $postcode ) {
		$this->postcode = $postcode;

		return $this;
	}

	/**
	 * Helper method for adding address country Code. The list of country
	 * codes is available in the Realauth developers guide.
	 *
	 * @param string $countryCode
	 *
	 * @return PayerAddress
	 */
	public function addCountryCode( $countryCode ) {
		if ( is_null( $this->country ) ) {
			$this->country = new Country();
		}

		$this->country->setCode( $countryCode );

		return $this;
	}


	/**
	 * Helper method for adding address country name.
	 *
	 * @param string $countryName
	 *
	 * @return PayerAddress
	 */
	public function addCountryName( $countryName ) {
		if ( is_null( $this->country ) ) {
			$this->country = new Country();
		}

		$this->country->setName( $countryName );

		return $this;
	}

}