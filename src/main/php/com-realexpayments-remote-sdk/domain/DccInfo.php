<?php


namespace com\realexpayments\remote\sdk\domain;

/**
 * Class DccInfo
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing DCC information to be passed to Realex.
 * </p>
 * <p/>
 * <p>
 * Example dcc rate lookup:
 * </p>
 *
 * <p><code><pre>
 * $dccInfo = ( new DccInfo() )
 *  ->addDccProcessor("fexco");
 * <p/>
 * </pre></code></p>
 * <p/>
 * <p>
 * Example auth with dcc information:
 * </p>
 * <p><code><pre>
 *
 * $dccInfo = ( new DccInfo() )
 * ->addDccProcessor("fexco")
 * ->addRate(0.6868)
 * ->addAmount(13049)
 * ->addCurrency("GBP");
 * $request = ( new PaymentRequest() )
 * ->addMerchantId( "Merchant ID" )
 * ->addAccount( "internet" )
 * ->addType( PaymentType::DCC_RATE_LOOKUP )
 * ->addAmount(100)
 * ->addCurrency( "EUR" )        
 * ->addCard( $card )
 * ->addDccInfo( $dccInfo );
 *
 * <p/>
 * </pre></code></p>
 */
class DccInfo {

	/**
	 * @var string The DCC processor (Currency Conversion Processor)
	 */
	private $dccProcessor;

	/**
	 * @var string The type - always "1"
	 */
	private $type;

	/**
	 * @var float The rate returned by DCC Info rate lookup request
	 */
	private $rate;

	/**
	 * @var string The rate type. Defaulted to S
	 */
	private $rateType;

	/**
	 * @var Amount The amount. As returned by DCC Info rate lookup request
	 */
	private $amount;

	/**
	 * DccInfo constructor.
	 */
	public function __construct() {

		// default type to 1 and rate type to "S"
		$this->type     = "1";
		$this->rateType = "S";
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for dccProcessor
	 *
	 * @return string
	 */
	public function getDccProcessor() {
		return $this->dccProcessor;
	}

	/**
	 * Setter for dccProcessor
	 *
	 * @param string $dccProcessor
	 */
	public function setDccProcessor( $dccProcessor ) {
		$this->dccProcessor = $dccProcessor;
	}

	/**
	 * Getter for type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Setter for type
	 *
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter for rate
	 *
	 * @return float
	 */
	public function getRate() {
		return $this->rate;
	}

	/**
	 * Setter for rate
	 *
	 * @param float $rate
	 */
	public function setRate( $rate ) {
		$this->rate = $rate;
	}

	/**
	 * Getter for rateType
	 *
	 * @return string
	 */
	public function getRateType() {
		return $this->rateType;
	}

	/**
	 * Setter for rateType
	 *
	 * @param string $rateType
	 */
	public function setRateType( $rateType ) {
		$this->rateType = $rateType;
	}

	/**
	 * Getter for amount
	 *
	 * @return Amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Setter for amount
	 *
	 * @param Amount $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}


	/**
	 * Helper method for adding a dccProcessor
	 *
	 * @param string $dccProcessor
	 *
	 * @return DccInfo
	 */
	public function addDccProcessor( $dccProcessor ) {
		$this->dccProcessor = $dccProcessor;

		return $this;
	}

	/**
	 * Helper method for adding a type
	 *
	 * @param string $type
	 *
	 * @return DccInfo
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method for adding a rate
	 *
	 * @param float $rate
	 *
	 * @return DccInfo
	 */
	public function addRate( $rate ) {
		$this->rate = $rate;

		return $this;
	}

	/**
	 * Helper method for adding a rateType
	 *
	 * @param string $rateType
	 *
	 * @return DccInfo
	 */
	public function addRateType( $rateType ) {
		$this->rateType = $rateType;

		return $this;
	}

	/**
	 * Helper method for adding a amount
	 *
	 * @param integer $amount
	 *
	 * @return DccInfo
	 */
	public function addAmount( $amount ) {

		if ( is_null( $this->amount ) ) {
			$this->amount = new Amount();
			$this->amount->addAmount( $amount );
		} else {
			$this->amount->addAmount( $amount );
		}

		return $this;
	}

	/**
	 * Helper method for adding a currency
	 *
	 * @param string $currency
	 *
	 * @return DccInfo
	 */
	public function addCurrency( $currency ) {
		if ( is_null( $this->amount ) ) {
			$this->amount = new Amount();
			$this->amount->addCurrency( $currency );
		} else {
			$this->amount->addCurrency( $currency );
		}

		return $this;
	}


}