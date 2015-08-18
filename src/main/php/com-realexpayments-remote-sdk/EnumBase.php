<?php


namespace com\realexpayments\remote\sdk;


use ReflectionClass;

/**
 * Class EnumBase
 * Class used to emulate the enum functionality
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk
 */
class EnumBase {

	const __default = null;

	/**
	 * EnumBase Constructor
	 *
	 * @param string $value
	 */
	public function __construct( $value ) {
		if ( is_null( $value ) ) {
			$value = $this::__default;
		}

		/*if ( ! $this->isValidValue( $value ) ) {
			throw new \InvalidArgumentException();
		}*/
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	final public function __toString() {
		return $this->value;
	}

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public static function isValidValue( $value ) {
		$c = new ReflectionClass( get_called_class() );
		if ( in_array( $value, $c->getConstants() ) ) {
			return true;
		}

		return false;
	}
}