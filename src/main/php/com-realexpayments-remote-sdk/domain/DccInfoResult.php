<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Class DccInfoResult
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing DCC information returned by Realex.
 * </p>
 * <p/>
 * <p><code><pre>
 * $dccInfo = new DccInfoResult();
 * $dccInfo->setCardHolderCurrency("GBP");
 * $dccInfo->setCardHolderAmount(13049);
 * $dccInfo->setCardHolderRate(0.6868);
 * $dccInfo->setMerchantCurrency("EUR");
 * $dccInfo->setMerchantAmount("19000");
 *
 * <p/>
 * </pre></code></p>
 *
 * @author vicpada
 */
class DccInfoResult {

	/**
	 * @var string The currency of the card holder
	 */
	private $cardHolderCurrency;

	/**
	 * @var int The amount on the card holder currency
	 */
	private $cardHolderAmount;

	/**
	 * @var float The rate used to calculate the card holder amount
	 */
	private $cardHolderRate;

	/**
	 * @var string The currency used by the merchant
	 */
	private $merchantCurrency;

	/**
	 * @var int The amount in the currency of the merchant
	 */
	private $merchantAmount;

	/**
	 * @var string Percentage of the margin rate
	 */
	private $marginRatePercentage;

	/**
	 * @var string Name of the source that provides the exchange rate
	 */
	private $exchangeRateSourceName;

	/**
	 * @var string Commission Percentage
	 */
	private $commissionPercentage;

	/**
	 * @var string Timestamp for the exchange rate
	 */
	private $exchangeRateSourceTimestamp;

	/**
	 * DccInfoResult constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for cardHolderCurrency
	 *
	 * @return string
	 */
	public function getCardHolderCurrency() {
		return $this->cardHolderCurrency;
	}

	/**
	 * Setter for cardHolderCurrency
	 *
	 * @param string $cardHolderCurrency
	 */
	public function setCardHolderCurrency( $cardHolderCurrency ) {
		$this->cardHolderCurrency = $cardHolderCurrency;
	}

	/**
	 * Getter for cardHolderAmount
	 *
	 * @return int
	 */
	public function getCardHolderAmount() {
		return $this->cardHolderAmount;
	}

	/**
	 * Setter for cardHolderAmount
	 *
	 * @param int $cardHolderAmount
	 */
	public function setCardHolderAmount( $cardHolderAmount ) {
		$this->cardHolderAmount = $cardHolderAmount;
	}

	/**
	 * Getter for cardHolderRate
	 *
	 * @return float
	 */
	public function getCardHolderRate() {
		return $this->cardHolderRate;
	}

	/**
	 * Setter for cardHolderRate
	 *
	 * @param float $cardHolderRate
	 */
	public function setCardHolderRate( $cardHolderRate ) {
		$this->cardHolderRate = $cardHolderRate;
	}

	/**
	 * Getter for merchantCurrency
	 *
	 * @return string
	 */
	public function getMerchantCurrency() {
		return $this->merchantCurrency;
	}

	/**
	 * Setter for merchantCurrency
	 *
	 * @param string $merchantCurrency
	 */
	public function setMerchantCurrency( $merchantCurrency ) {
		$this->merchantCurrency = $merchantCurrency;
	}

	/**
	 * Getter for merchantAmount
	 *
	 * @return int
	 */
	public function getMerchantAmount() {
		return $this->merchantAmount;
	}

	/**
	 * Setter for merchantAmount
	 *
	 * @param int $merchantAmount
	 */
	public function setMerchantAmount( $merchantAmount ) {
		$this->merchantAmount = $merchantAmount;
	}

	/**
	 * Getter for marginRatePercentage
	 *
	 * @return string
	 */
	public function getMarginRatePercentage() {
		return $this->marginRatePercentage;
	}

	/**
	 * Setter for marginRatePercentage
	 *
	 * @param string $marginRatePercentage
	 */
	public function setMarginRatePercentage( $marginRatePercentage ) {
		$this->marginRatePercentage = $marginRatePercentage;
	}

	/**
	 * Getter for exchangeRateSourceName
	 *
	 * @return string
	 */
	public function getExchangeRateSourceName() {
		return $this->exchangeRateSourceName;
	}

	/**
	 * Setter for exchangeRateSourceName
	 *
	 * @param string $exchangeRateSourceName
	 */
	public function setExchangeRateSourceName( $exchangeRateSourceName ) {
		$this->exchangeRateSourceName = $exchangeRateSourceName;
	}

	/**
	 * Getter for commissionPercentage
	 *
	 * @return string
	 */
	public function getCommissionPercentage() {
		return $this->commissionPercentage;
	}

	/**
	 * Setter for commissionPercentage
	 *
	 * @param string $commissionPercentage
	 */
	public function setCommissionPercentage( $commissionPercentage ) {
		$this->commissionPercentage = $commissionPercentage;
	}

	/**
	 * Getter for exchangeRateSourceTimestamp
	 *
	 * @return string
	 */
	public function getExchangeRateSourceTimestamp() {
		return $this->exchangeRateSourceTimestamp;
	}

	/**
	 * Setter for exchangeRateSourceTimestamp
	 *
	 * @param string $exchangeRateSourceTimestamp
	 */
	public function setExchangeRateSourceTimestamp( $exchangeRateSourceTimestamp ) {
		$this->exchangeRateSourceTimestamp = $exchangeRateSourceTimestamp;
	}



	/**
	 * Helper method for adding a cardHolderCurrency
	 *
	 * @param string $cardHolderCurrency
	 *
	 * @return DccInfoResult
	 */
	public function addCardHolderCurrency( $cardHolderCurrency ) {
		$this->cardHolderCurrency = $cardHolderCurrency;

		return $this;
	}

	/**
	 * Helper method for adding a cardHolderAmount
	 *
	 * @param int $cardHolderAmount
	 *
	 * @return DccInfoResult
	 */
	public function addCardHolderAmount( $cardHolderAmount ) {
		$this->cardHolderAmount = $cardHolderAmount;

		return $this;
	}

	/**
	 * Helper method for adding a cardHolderRate
	 *
	 * @param float $cardHolderRate
	 *
	 * @return DccInfoResult
	 */
	public function addCardHolderRate( $cardHolderRate ) {
		$this->cardHolderRate = $cardHolderRate;

		return $this;
	}

	/**
	 * Helper method for adding a merchantCurrency
	 *
	 * @param string $merchantCurrency
	 *
	 * @return DccInfoResult
	 */
	public function addMerchantCurrency( $merchantCurrency ) {
		$this->merchantCurrency = $merchantCurrency;

		return $this;
	}

	/**
	 * Helper method for adding a merchantAmount
	 *
	 * @param int $merchantAmount
	 *
	 * @return DccInfoResult
	 */
	public function addMerchantAmount( $merchantAmount ) {
		$this->merchantAmount = $merchantAmount;

		return $this;
	}

	/**
	 * Helper method for adding a marginRatePercentage
	 *
	 * @param string $marginRatePercentage
	 *
	 * @return DccInfoResult
	 */
	public function addMarginRatePercentage( $marginRatePercentage ) {
		$this->marginRatePercentage = $marginRatePercentage;

		return $this;
	}

	/**
	 * Helper method for adding a exchangeRateSourceName
	 *
	 * @param string $exchangeRateSourceName
	 *
	 * @return DccInfoResult
	 */
	public function addExchangeRateSourceName( $exchangeRateSourceName ) {
		$this->exchangeRateSourceName = $exchangeRateSourceName;

		return $this;
	}

	/**
	 * Helper method for adding a commissionPercentage
	 *
	 * @param string $commissionPercentage
	 *
	 * @return DccInfoResult
	 */
	public function addCommissionPercentage( $commissionPercentage ) {
		$this->commissionPercentage = $commissionPercentage;

		return $this;
	}

	/**
	 * Helper method for adding a exchangeRateSourceTimestamp
	 *
	 * @param string $exchangeRateSourceTimestamp
	 *
	 * @return DccInfoResult
	 */
	public function addExchangeRateSourceTimestamp( $exchangeRateSourceTimestamp ) {
		$this->exchangeRateSourceTimestamp = $exchangeRateSourceTimestamp;

		return $this;
	}




}