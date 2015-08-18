<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure;



/**
 * <p>
 * Domain object representing 3D Secure (realmpi) information passed back from Realex.
 * Realmpi is a real time card holder verification system to assist a merchant with the
 * identification of potentially fraudulent transactions.
 * </p>
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\threeDSecure
 *
 */
class ThreeDSecure {

	/**
	 * @var string The outcome of the authentication, required for the authorisation request.
	 *
	 */
	private $status;

	/**
	 * @var string The e-commerce indicator, required for the authorisation request.
	 *
	 */
	private $eci;

	/**
	 * @var string The XID field, required for the authorisation request.
	 *
	 */
	private $xid;

	/**
	 * @var string The CAVV or UCAF, required for the authorisation request.
	 *
	 */
	private $cavv;

	/**
	 * @var string The address of the customer.
	 *
	 */
	private $algorithm;

	/**
	 * ThreeDSecure constructor.
	 */
	public function __construct() {
	}

	/**
	 * Getter for status
	 *
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Setter for status
	 *
	 * @param string $status
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}

	/**
	 * Getter for eci
	 *
	 * @return string
	 */
	public function getEci() {
		return $this->eci;
	}

	/**
	 * Setter for eci
	 *
	 * @param string $eci
	 */
	public function setEci( $eci ) {
		$this->eci = $eci;
	}

	/**
	 * Getter for xid
	 *
	 * @return string
	 */
	public function getXid() {
		return $this->xid;
	}

	/**
	 * Setter for xid
	 *
	 * @param string $xid
	 */
	public function setXid( $xid ) {
		$this->xid = $xid;
	}

	/**
	 * Getter for cavv
	 *
	 * @return string
	 */
	public function getCavv() {
		return $this->cavv;
	}

	/**
	 * Setter for cavv
	 *
	 * @param string $cavv
	 */
	public function setCavv( $cavv ) {
		$this->cavv = $cavv;
	}

	/**
	 * Getter for algorithm
	 *
	 * @return string
	 */
	public function getAlgorithm() {
		return $this->algorithm;
	}

	/**
	 * Setter for algorithm
	 *
	 * @param string $algorithm
	 */
	public function setAlgorithm( $algorithm ) {
		$this->algorithm = $algorithm;
	}


	/**
	 * Helper method for adding a status
	 *
	 * @param string $status
	 *
	 * @return ThreeDSecure
	 */
	public function addStatus( $status ) {
		$this->status = $status;

		return $this;
	}

	/**
	 * Helper method for adding a eci
	 *
	 * @param string $eci
	 *
	 * @return ThreeDSecure
	 */
	public function addEci( $eci ) {
		$this->eci = $eci;

		return $this;
	}

	/**
	 * Helper method for adding a xid
	 *
	 * @param string $xid
	 *
	 * @return ThreeDSecure
	 */
	public function addXid( $xid ) {
		$this->xid = $xid;

		return $this;
	}

	/**
	 * Helper method for adding a cavv
	 *
	 * @param string $cavv
	 *
	 * @return ThreeDSecure
	 */
	public function addCavv( $cavv ) {
		$this->cavv = $cavv;

		return $this;
	}

	/**
	 * Helper method for adding a algorithm
	 *
	 * @param string $algorithm
	 *
	 * @return ThreeDSecure
	 */
	public function addAlgorithm( $algorithm ) {
		$this->algorithm = $algorithm;

		return $this;
	}


}