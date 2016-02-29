<?php


namespace com\realexpayments\remote\sdk\domain\payment;

/**
 * Class Address
 * <p>
 * The Address of the customer
 * </p>
 *
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p><code>
 * $address = (new Address())->addCode("774|123")->addCountry("GB")->addType(new AddressType(AddressType::SHIPPING));
 * </code></p>
 *  *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 */
class Address {


	/**
	 * @var string The address type. Can be shipping or billing.
	 *
	 */
	private $type;

	/**
	 * @var string The ZIP|Postal code of the address. This can be checked (in conjunction with the country)
	 * against a table of high-risk area codes. This field is used address verification with certain acquirers.
	 *
	 */
	private $code;

	/**
	 * @var string The country of the address. This can be checked against a table of high-risk countries.
	 *
	 */
	private $country;

	/**
	 * Address constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * @return string Getter for the type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * Setter for the type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * @return string Getter for the code.
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param mixed $code
	 * Setter for the code
	 */
	public function setCode( $code ) {
		$this->code = $code;
	}

	/**
	 * @return string Getter for the country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Setter for the country
	 *
	 * @param string $country
	 */
	public function setCountry( $country ) {
		$this->country = $country;
	}


	/**
	 * Helper method for setting the code
	 *
	 * @param string $code
	 *
	 * @return $this
	 */
	public function addCode( $code ) {
		$this->code = $code;

		return $this;
	}


	/**
	 * Helper method for setting the country
	 *
	 * @param string $country
	 *
	 * @return $this
	 */
	public function addCountry( $country ) {
		$this->country = $country;

		return $this;
	}


	/**
	 * Helper method for setting the type
	 *
	 * @param AddressType|string $type
	 *
	 * @return $this
	 */
	public function addType( $type ) {
		if ( $type instanceof AddressType ) {
			$this->type = $type->getAddressType();
		} else {
			$this->type = $type;
		}

		return $this;
	}
}