<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * Class FraudFilterRuleCollection, used to hold a collection of rules
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author alessandro
 */
class FraudFilterRuleCollection {


	/**
	 * @var FraudFilterRule[] List of {@link FraudFilterRule} objects to be passed in request. Optionally, up to two rules
	 * can be associated with any transaction.
	 *
	 */
	private $rules;

	/**
	 * FraudFilterRuleCollection constructor.
	 *
	 */
	public function __construct() {
		$this->rules = array();
	}

	/**
	 * Getter for rules
	 *
	 * @return FraudFilterRule[]
	 */
	public function getRules() {
		return $this->rules;
	}

	/**
	 * Setter for rules
	 *
	 * @param FraudFilterRule[] $rules
	 */
	public function setRules( $rules ) {
		$this->rules = $rules;
	}

	/**
	 * Get FraudFilterRule at index
	 *
	 * @param $index
	 *
	 * @return FraudFilterRule
	 */
	public function get( $index ) {
		return $this->rules[ $index ];
	}


	/**
	 * Set FraudFilterRule at index
	 *
	 * @param $index
	 * @param FraudFilterRule $action
	 */
	public function set( $index, FraudFilterRule $action ) {
		$this->rules[ $index ] = $action;
	}

	/**
	 * Add a new FraudFilterRule
	 *
	 * @param FraudFilterRule $value
	 */
	public function add( FraudFilterRule $value ) {
		$this->rules[] = $value;
	}

	public function getSize() {
		return count( $this->rules );
	}
}