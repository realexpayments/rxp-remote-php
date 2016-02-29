<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * <p>
 * Class representing the AutoSettle flag in a Realex request. If set to TRUE (1),
 * then the transaction will be included in today's settlement file. If set to FALSE (0), then the
 * transaction will be authorised but not settled. Merchants must manually settle delayed
 * transactions within 28 days of authorisation.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example creation:
 * </p>
 * <p><code><pre>
 * $autoSettle = (new AutoSettle())->addFlag(AutoSettleFlag::TRUE);
 * </pre></code></p>
 *
 * @author vicpada
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 *
 */
class AutoSettle {

	/**
	 * @var string The AutoSettle flag value.
	 *
	 */
	private $flag;

	/**
	 * AutoSettle constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for flag
	 *
	 * @return string
	 */
	public function getFlag() {
		return $this->flag;
	}

	/**
	 * Setter for flag
	 *
	 * @param string $flag
	 */
	public function setFlag( $flag ) {
		$this->flag = $flag;
	}

	/**
	 * Helper method for adding the flag value
	 *
	 * @param string|AutoSettleFlag $flag
	 *
	 * @return AutoSettle
	 */
	public function addFlag( $flag ) {
		if ( $flag instanceof AutoSettleFlag ) {
			$this->flag = $flag->getFlag();
		} else {
			$this->flag = $flag;
		}

		return $this;
	}
}

