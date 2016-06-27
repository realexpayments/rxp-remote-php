<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * Class ThreeDSecureType
 * Enumeration for the ThreeDSecure type.
 *
 * @package com\realexpayments\remote\sdk\domain\threeDSecure
 */
class ThreeDSecureType extends EnumBase{

	const __default = self::VERIFY_ENROLLED;

	const VERIFY_ENROLLED = "3ds-verifyenrolled";

	const VERIFY_SIG = "3ds-verifysig";

	const VERIFY_STORED_CARD_ENROLLED  = "realvault-3ds-verifyenrolled";

	/**
	 * @var string The ThreeDSecure type String value
	 */
	private $type;

	/**
	 * ThreeDSecureType constructor
	 *
	 * @param string $type
	 */
	public function __construct( $type ) {
		parent::__construct( $type );

		$this->type = $type;
	}

	/**
	 * Get the string value of the ThreeDSecure type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

}
