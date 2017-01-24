<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Class Country
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing Country information to be passed to Realex.
 * </p>
 *
 * <p><code><pre>
 *
 * $country = new Country();
 * country->setCode("IE");
 * country->setName("Ireland");
 *
 * </pre></code></p>
 *
 * @author vicpada
 */
class Country {


	/**
	 * @var string The country code. The list of country codes is available
	 * in the realauth developers guide.
	 */
	private $code;

	/**
	 * @var string The country name.
	 */
	private $name;

	/**
	 * Country constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for code
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Setter for code
	 *
	 * @param string $code
	 */
	public function setCode( $code ) {
		$this->code = $code;
	}

	/**
	 * Getter for name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Setter for name
	 *
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

}