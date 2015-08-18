<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\RealexException;
use com\realexpayments\remote\sdk\RXPLogger;
use Logger;

/**
 * Utils class offering methods which act on the Realex response.
 *
 * @package com\realexpayments\remote\sdk\utils
 * @author vicpada
 */
class ResponseUtils {

	/**
	 * @var Logger logger
	 */
	private static $logger;

	private static $initialised = false;

	/**
	 * Returns <code>true</code> if the response contains a result code representing success.
	 *
	 * @param iResponse $response
	 *
	 * @return bool
	 */
	public static function isSuccess( iResponse $response ) {

		return iResponse::RESULT_CODE_SUCCESS == $response->getResult();
	}


	/**
	 * <p>
	 * Realex responses can be basic or full. A basic response indicates the request could not
	 * be processed. In this case a {@link RealexServerException} will be thrown by the SDK containing the
	 * result code and message from the response.
	 * </p>
	 *
	 * <p>
	 * A full response indicates the request could be processed and the response object will return fully populated.
	 * </p>
	 *
	 * <p>
	 * Please note, full responses may still contain errors e.g. Bank errors (1xx). The result code should be
	 * checked for success. For example a full response with a result code of 101 will not throw an exception and will return
	 * a fully populated response object.
	 * </p>
	 *
	 * @param string $result
	 *
	 * @return bool
	 */
	public static function isBasicResponse( $result ) {
		self::Initialise();

		$inErrorRange = false;

		try {
			$initialNumber = intval( substr( $result, 0, 1 ) );
			$inErrorRange  = $initialNumber >= iResponse::RESULT_CODE_PREFIX_ERROR_RESPONSE_START;
		} catch ( \Exception $e ) {
			self::$logger->error( "Error parsing result " . $result, $e );
			throw new RealexException("Error parsing result.", $e);
		}

		return $inErrorRange;
	}

	private static function Initialise() {
		if ( self::$initialised ) {
			return;
		}

		self::$logger = RXPLogger::getLogger( __CLASS__ );

		self::$initialised = true;
	}
}