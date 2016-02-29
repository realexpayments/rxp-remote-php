<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Class PaymentData
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing PaymentData information to be passed to Realex Card Storage
 * for Receipt-in transactions.
 * Payment data contains the CVN number for the stored card
 * </p>
 * <p/>
 * <p><code><pre>
 * $paymentData = ( new PaymentData() )
 *    ->addCvnNumber("123") ;
 * </pre></code></p>
 *
 * @author vicpada
 */
class PaymentData {

	/**
	 * @var CvnNumber A container for the CVN Number
	 */
	private $cvnNumber;

	/**
	 * PaymentData constructor.
	 */
	public function __construct() {
	}

	/**
	 * Getter for cvnNumber
	 *
	 * @return CvnNumber
	 */
	public function getCvnNumber() {
		return $this->cvnNumber;
	}

	/**
	 * Setter for cvnNumber
	 *
	 * @param CvnNumber $cvnNumber
	 */
	public function setCvnNumber( $cvnNumber ) {
		$this->cvnNumber = $cvnNumber;
	}

	/**
	 * Helper method for adding a cvnNumber
	 *
	 * @param CvnNumber|string $cvnNumber
	 *
	 * @return PaymentData
	 */
	public function addCvnNumber( $cvnNumber ) {

		if ( $cvnNumber instanceof CvnNumber ) {
			$this->cvnNumber = $cvnNumber;
		} else {
			if ( is_null( $this->cvnNumber ) ) {
				$this->cvnNumber = new CvnNumber();
			}
			$this->cvnNumber->addNumber( $cvnNumber );
		}

		return $this;
	}

	public static function GetClassName() {
		return __CLASS__;
	}

}