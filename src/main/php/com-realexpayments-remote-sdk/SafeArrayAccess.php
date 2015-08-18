<?php


namespace com\realexpayments\remote\sdk;


use ArrayAccess;

class SafeArrayAccess implements ArrayAccess {
	private $array;
	private $default;

	public function __construct( array $array, $default = null ) {
		$this->array   = $array;
		$this->default = $default;
	}

	public function offsetExists( $offset ) {
		return isset( $this->array[ $offset ] );
	}

	public function offsetGet( $offset ) {
		return isset( $this->array[ $offset ] )
			? $this->array[ $offset ]
			: $this->default;
	}

	public function offsetSet( $offset, $value ) {
		$this->array[ $offset ] = $value;
	}

	public function offsetUnset( $offset ) {
		unset( $this->array[ $offset ] );
	}
}