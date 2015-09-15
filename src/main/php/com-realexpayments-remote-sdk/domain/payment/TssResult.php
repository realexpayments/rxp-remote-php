<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * The results of realscore checks.
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 *
 */
class TssResult {

	/**
	 * @var string The weighted total score of realscore. The weights can be adjusted in the realcontrol application.
	 *
	 */
	private $result;

	/**
	 * @var TssResultCheck[] The list of realscore check results.
	 *
	 */
	private $checks;

	/**
	 * Getter for result
	 *
	 * @return string
	 */
	public function getResult() {
		return $this->result;
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
	 * @return TssResultCheck[]
	 */
	public function getChecks() {
		return $this->checks;
	}

	/**
	 * Setter for checks
	 *
	 * @param TssResultCheck[] $checks
	 */
	public function setChecks( $checks ) {
		$this->checks = $checks;
	}

	/**
	 * The __toString method allows a class to decide how it will react when it is converted to a string.
	 *
	 * @return string representation in format of <overall_result>:<check_id>-<check_result>;<check_id>-<check_result>;...
	 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	 */
	function __toString() {
		$result  = $this->getResult();
		$result .= ":";


		foreach($this->getChecks() as $check)
		{
			$result .= $check->getId();
			$result .= "-";
			$result .= $check->getValue();
			$result .= ";";
		}

		return $result;
	}


}