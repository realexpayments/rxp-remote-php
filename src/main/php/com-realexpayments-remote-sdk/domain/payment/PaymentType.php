<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * Class PaymentType
 * Enumeration for the Payment type.
 */
class PaymentType  extends EnumBase {

	const __default = self::AUTH;
	const  AUTH = "auth";
	const  AUTH_MOBILE = "auth-mobile";

	/**
	 * @var string The payment type String value
	 */
	private $type;


	/**
	 * @param string $type
	 */
	public function __construct($type)
	{
		parent::__construct($type);
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