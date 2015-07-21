<?php


namespace com\realexpayments\remote\sdk\domain\payment;

class AutoSettle {

	/**
	 * @var string The AutoSettle flag value.
	 */
	private $flag;

	/**
	 * AutoSettle constructor.
	 */
	public function __construct() {
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
	 * @param string $flag
	 * @return AutoSettle
	 */
	public function addFlag( $flag ) {
		$this->flag = $flag;

		return $this;
	}

	/**
	 * Helper method for adding the {@link AutoSettle} value.
	 *
	 * @param AutoSettleFlag $flag
	 * @return AutoSettle
	 */
	public function addAutoSettleFlag( AutoSettleFlag $flag ) {
		$this->flag = $flag->getFlag();
		return $this;
	}
}

