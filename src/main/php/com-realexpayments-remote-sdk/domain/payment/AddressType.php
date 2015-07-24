<?php


namespace com\realexpayments\remote\sdk\domain\payment;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * The address type enum. Can be shipping or billing.
 */
class AddressType extends EnumBase {

	const __default = self::NONE;

	const NONE = "None";
	const SHIPPING = "shipping";
	const BILLING = "billing";

	/**
	 * @var string The type value
	 */
	private $addressType;


	/**
	 * Constructor for the enum
	 *
	 * @param string $addressType
	 */
	public function __construct($addressType)
	{
		parent::__construct($addressType);
		$this->addressType = $addressType;
	}

	/**
	 * Returns string value for the type
	 *
	 * @return string
	 */
	public function getAddressType() {
		return $this->addressType;
	}




}