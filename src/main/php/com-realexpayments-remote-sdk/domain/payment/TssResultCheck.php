<?php


namespace com\realexpayments\remote\sdk\domain\payment;


/**
 * Domain object representing the results of an individual realscore check.
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 *
 */
class TssResultCheck {

	/**
	 * @var string The ID of the realscore check
	 *
	 */
	private $id;

	/**
	 * @var string The value of the realscore check
	 *
	 */
	private $value;

	/**
	 * Getter for id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Setter for id
	 *
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * Getter for value
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Setter for value
	 *
	 * @param string $value
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}

}