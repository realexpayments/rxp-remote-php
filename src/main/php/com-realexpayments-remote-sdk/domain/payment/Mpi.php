<?php


namespace com\realexpayments\remote\sdk\domain\payment;


/**
 * <p>
 * Domain object representing MPI (realmpi) information to be passed to Realex.
 * Realmpi is Realex's product to implement card scheme-certified payer authentication via the bank
 * and the 3D Secure system (Verified by Visa for Visa, Secure Code for Mastercard and SafeKey for Amex).
 * </p>
 *
 * <p><code><pre>
 * $mpi = (new Mpi())
 * 	->addCavv("cavv")
 * 	->addXid("xid")
 * 	->addEci("eci");
 * </pre></code></p>
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\payment
 *
 */
class Mpi {

	/**
	 * @var string The CAVV(Visa)/UCAF(Mastercard) if present.
	 *
	 */
	private $cavv;

	/**
	 * @var string The XID.
	 *
	 */
	private $xid;

	/**
	 * The e-commerce indicator.
	 * <th>
	 * 		<td>Visa</td><td>MC</td><td>ECI</td>
	 * </th>
	 * <tr>
	 * 		<td>5</td><td>2</td><td>Fully secure, card holder enrolled</td>
	 * </tr>
	 * <tr>
	 * 		<td>6</td><td>1</td><td>Merchant secure, card holder not enrolled or attempt ACS server was used</td>
	 * </tr>
	 * <tr>
	 * 		<td>7</td><td>0</td><td>Transaction not secure</td>
	 * </tr>
	 * <li>
	 *
	 * @var string The e-commerce indicator.
	 *
	 */
	private $eci;

	/**
	 * Mpi constructor.
	 */
	public function __construct() {
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
	 * Helper method for adding a cavv
	 *
	 * @param string $cavv
	 *
	 * @return Mpi
	 */
	public function addCavv( $cavv ) {
		$this->cavv = $cavv;

		return $this;
	}

	/**
	 * Helper method for adding a xid
	 *
	 * @param string $xid
	 *
	 * @return Mpi
	 */
	public function addXid( $xid ) {
		$this->xid = $xid;

		return $this;
	}

	/**
	 * Helper method for adding a eci
	 *
	 * @param string $eci
	 *
	 * @return Mpi
	 */
	public function addEci( $eci ) {
		$this->eci = $eci;

		return $this;
	}




}