<?php


namespace com\realexpayments\remote\sdk\domain\payment;


/**
 * Domain object representing the results of an individual fraudfilter result check.
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author alessandro
 *
 */
class FraudFilterRule {

	/**
	 * @var string The ID of the fraud filter rule
	 *
	 */
	private $id;

	/**
	 * @var string The name of the fraud filter rule
	 *
	 */
	private $name;

	/**
	 * @var string The value of the fraud filter rule
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

	/**
	 * Setter for name
	 *
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * Getter for name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

}