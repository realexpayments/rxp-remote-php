<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use SplEnum;

class AutoSettleFlag extends SplEnum {
	const TRUE = "1";
	const FALSE = "0";
	const MULTI = "MULTI";

	/**
	 * @var string The flag field
	 */
	private $flag;

	/**
	 * AutoSettleFlag constructor.
	 *
	 * @param string $flag
	 */
	public function __construct( $flag ) {
		$this->flag = $flag;
	}


	/**
	 * Getter for flag
	 *
	 * @return string
	 */
	public function getFlag() {
		return $this->flag;
	}
}