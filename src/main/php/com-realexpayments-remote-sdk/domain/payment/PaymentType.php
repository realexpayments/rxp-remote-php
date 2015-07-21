<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use SplEnum;

/**
 * Class PaymentType
 * Enumeration for the Payment type.
 */
class PaymentType  extends SplEnum {

	const __default = self::AUTH;
	const  AUTH = "auth";

	/**
	 * @var string The payment type String value
	 */
	private $type;


	/**
	 * @param string $type
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}

	/**
	 * Get the string value of the payment type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

}