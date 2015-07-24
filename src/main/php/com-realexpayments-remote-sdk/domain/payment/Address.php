<?php


namespace com\realexpayments\remote\sdk\domain\payment;
use Doctrine\OXM\Mapping as DOM;


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
 * Address $address = new (Address())->addCode("D2")->addCountry("IE")->addType(new AddressType(AddressType::Billing));
 * </code></p>
 *  *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 *
 * @Dom\XmlEntity
 */
class Address {


	/**
	 * @var string The address type. Can be shipping or billing.
	 *
	 * @Dom\XmlAttribute(type="string",name="type")
	 */
	private $type;

	/**
	 * @var string The ZIP|Postal code of the address. This can be checked (in conjunction with the country)
	 * against a table of high-risk area codes. This field is used address verification with certain acquirers.
	 *
	 * @Dom\XmlText(type="string",name="code")
	 */
	private $code;

	/**
	 * @var string The country of the address. This can be checked against a table of high-risk countries.
	 *
	 * @Dom\XmlText(type="string",name="country")
	 */
	private $country;

	/**
	 * Address constructor.
	 */
	public function __construct() {
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
	 * @param string $country
	 */
	public function setCountry( $country ) {
		$this->country = $country;
	}


	/**
	 * Helper method for setting the code
	 *
	 * @param $code
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
	 * @param $country
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
	 * @param $type
	 *
	 * @return $this
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method for setting the type
	 *
	 * @param $addressType
	 *
	 * @return $this
	 */
	public function addAddressType( AddressType $addressType ) {
		$this->type = $addressType->getAddressType();

		return $this;
	}

}