<?php


namespace com\realexpayments\remote\sdk\domain;


use com\realexpayments\remote\sdk\EnumBase;


/**
 * Enumeration representing card types.
 */
class CardType extends EnumBase {

	const __default = self::VISA;

	const VISA = "VISA";
	const MASTERCARD = "MC";
	const AMEX = "AMEX";
	const CB = "CB";
	const DINERS = "DINERS";
	const JCB = "JCB";

	/**
	 * @var string The card type
	 */
	private $type;

	/**
	 * @param string $type
	 */
	public function __construct( $type ) {
		parent::__construct( $type );

		$this->type = $type;
	}

	/**
	 * Getter for the card type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

}