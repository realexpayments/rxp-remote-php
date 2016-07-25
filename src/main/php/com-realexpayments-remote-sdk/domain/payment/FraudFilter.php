<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * <p>
 * Class representing the FraudFilter mode in a Realex request. This optional XML element is used to
 * determine to what degree Fraud Filter executes. If the field is not present, Fraud Filter
 * will behave in accordance with the RealControl mode configuration. Please note values are case sensitive.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>

 *
 * @author alessandro
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 *
 */
class FraudFilter {

	/**
	 * @var string The FraudFilter mode value.
	 *
	 */
	private $mode;

	/**
	 * @var string The result of the fraud filter request
	 *
	 */
	private $result;

	/**
	 * @var FraudFilterRuleCollection The list of fraud filter rules.
	 *
	 */
	private $rules;

	/**
	 * Getter for result
	 *
	 * @return string
	 */

	/**
	 * FraudFilter constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for mode
	 *
	 * @return string
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * Setter for mode
	 *
	 * @param string $mode
	 */
	public function setMode( $mode ) {
		$this->mode = $mode;
	}

	/**
	 * Helper method for adding the mode value
	 *
	 * @param string|FraudFilterMode $mode
	 *
	 * @return FraudFilter
	 */
	public function addMode( $mode ) {
		if ( $mode instanceof FraudFilterMode ) {
			$this->mode = $mode->getMode();
		} else {
			$this->mode = $mode;
		}

		return $this;
	}

	public function getResult() {
		return $this->result;
	}

	/**
	 * Setter for result
	 *
	 * @param string $result
	 * @return  FraudFilter
	 */
	public function addResult( $result ) {
		$this->result = $result;

		return $this;
	}

	/**
	 * Setter for result
	 *
	 * @param string $result
	 */
	public function setResult( $result ) {
		$this->result = $result;
	}

	/**
	 * Getter for checks
	 *
	 * @return FraudFilterRuleCollection
	 */
	public function getRules() {
		return $this->rules;
	}

	/**
	 * Setter for checks
	 *
	 * @param FraudFilterRuleCollection $rules
	 */
	public function setRules( $rules ) {
		$this->rules = $rules;
	}

	/**
	 * Setter for checks
	 *
	 * @param FraudFilterRuleCollection $rules
	 * @return  FraudFilter
	 */
	public function addRules( $rules ) {
		$this->rules = $rules;

		return $this;
	}


	/**
	 * The __toString method allows a class to decide how it will react when it is converted to a string.
	 *
	 * @return string representation in format of <overall_result>:<rule_id>-<rule-name>-<rule-result>;<rule_id>-<rule-name>-<rule-result>;...
	 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	 */
	function __toString() {
		$result  = $this->getResult();
		$result .= ":";

		$rules = $this->getRules();
		if(is_array($rules)){
			foreach($this->getRules() as $rule)
			{
				/**
				 * @var FraudFilterRule $rule
				 */
				$result .= $rule->getId();
				$result .= "-";
				$result .= $rule->getName();
				$result .= "-";
		 		$result .= $rule->getAction();
				$result .= ";";
			}
		}

		return $result;
	}
}

