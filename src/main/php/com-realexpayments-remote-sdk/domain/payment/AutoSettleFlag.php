<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use com\realexpayments\remote\sdk\EnumBase;
	
class AutoSettleFlag extends EnumBase {
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
		parent::__construct($flag);
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