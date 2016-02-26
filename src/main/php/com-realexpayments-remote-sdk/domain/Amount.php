<?php


namespace com\realexpayments\remote\sdk\domain;



/**
 * Class Amount
 *
 * * <p>
 * Class representing the Amount in a Realex request.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example creation:
 * </p>
 * <p><code><pre>
 * $amount = (new Amount())->addAmount(1001)->addCurrency("EUR");
 * </pre></code></p>
 *
 * @author vicpada
 *
 * @package com\realexpayments\remote\sdk\domain
 *
 */
class Amount {

	/**
	 * @var int The amount should be in the smallest unit of the required currency (For example: 2000=20 euro, dollar or pounds).
	 *
	 */
	private $amount;

	/**
	 * @var string The type of curency, e.g. GBP (Sterling) or EUR (Euro)
	 *
	 */
	private $currency;


	/**
	 * Amount constructor
	 */
	public function __construct()
	{

	}

	/**
	 * Getter for amount
	 *
	 * @return int
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Setter for amount
	 *
	 * @param int $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	/**
	 * Getter for currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * Setter for currency
	 *
	 * @param string $currency
	 */
	public function setCurrency( $currency ) {
		$this->currency = $currency;
	}

	/**
	 * Helper method for adding a currency
	 *
	 * @param string $currency
	 * @return Amount
	 */
	public function addCurrency( $currency ) {
		$this->currency = $currency;

		return $this;
	}

	/**
	 * Helper method for adding an amount
	 *
	 * @param int $amount
	 *
	 * @return Amount
	 */
	public function addAmount( $amount ) {
		$this->amount = $amount;

		return $this;
	}

	public static function GetClassName() {
		return __CLASS__;
	}

}