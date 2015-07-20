<?php


namespace com\realexpayments\remote\sdk;


use RuntimeException;

/**
 * An exception class for general Realex SDK errors. All other SDK exceptions will extend this class.
 *
 * @author vicpada
 */
class RealexException extends RuntimeException {

	const serialVersionUID = -6404893161440391367;

	/**
	 * Constructor for RealexException
	 * @param string $message
	 */
	function __construct($message){
		parent::__construct($message);
	}

}