<?php


namespace com\realexpayments\remote\sdk\http;

use com\realexpayments\remote\sdk\domain\iHttpClient;
use Logger;


/**
 * HTTP Utils class for dealing with HTTP and actual message sending.
 *
 * @author vicpada
 */
class HttpUtils {


	const HTTPS_PROTOCOL = 'https';

	/**
	 * @var Logger Logger
	 */
	private static $logger;


	/**
	 * @param HttpConfiguration $httpConfiguration
	 *
	 * @return iHttpClient httpclient
	 */
	public static function  getDefaultClient( HttpConfiguration $httpConfiguration ) {

		self::getLogger();

		// TODO: set Connect Timeout
		// TODO: set Socket Timeout

		self::$logger->debug( "Creating HttpClient with simple no pooling/no connection reuse default settings." );

		return new HttpClient();


	}

	/**
	 *
	 * Initialises logger if not initialised already
	 *
	 */
	private static function getLogger() {
		if ( ! self::$logger ) {
			self::$logger = Logger::getLogger( __CLASS__ );
		}
	}


}