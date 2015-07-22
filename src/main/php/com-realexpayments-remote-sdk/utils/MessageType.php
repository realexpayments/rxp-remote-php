<?php


namespace com\realexpayments\remote\sdk\utils;


use SplEnum;

class MessageType extends SplEnum  {

	const __default = self::PAYMENT;

	const PAYMENT = "Payment";
	const THREE_D_SECURE = "3DS";

	/**
	 * @var string The type
	 */
	private $type;

	/**
	 * Getter for type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * MessageType constructor.
	 *
	 * @param string $type
	 */
	public function __construct( $type ) {
		$this->type = $type;
	}


}