<?php


namespace com\realexpayments\remote\sdk\http;

use com\realexpayments\remote\sdk\RealexException;
use com\realexpayments\remote\sdk\RXPLogger;
use Logger;


/**
 * Class HttpClient. Wrapper for curl calls
 * @package com\realexpayments\remote\sdk\http
 */
class HttpClient {

	/**
	 * @var Logger Logger
	 */
	private $logger;

	/**
	 * @var int Timeout for the initial connection
	 */
	private $connectTimeout;

	/**
	 * @var int Timeout for the socket connection
	 */
	private $socketTimeout;

	/**
	 * HttpClient constructor.
	 */
	public function __construct() {
		$this->logger = RXPLogger::getLogger( __CLASS__ );
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param boolean $onlyAllowHttps
	 *
	 * @return HttpResponse
	 */
	public function execute( $httpRequest, $onlyAllowHttps = true ) {

		$url  = $httpRequest->getUrl();
		$post = $httpRequest->getMethod() == HttpRequest::METH_POST ? 1 : 0;
		$xml  = $httpRequest->getBody();

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, $post );
		curl_setopt( $ch, CURLOPT_USERAGENT, "realex sdk version 0.1" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/xml' ) );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout );
		curl_setopt( $ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout );

		if ( $onlyAllowHttps === false ) {
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		}

		$responseXml = curl_exec( $ch );
		$statusCode  = curl_getinfo( $ch, CURLINFO_HTTP_CODE );


		$errorNumber = curl_errno( $ch );
		if ( $errorNumber ) {
			$this->logger->error( "Exception communicating with Realex. Error number: " . $errorNumber . ". Description: " . curl_error( $ch ) );
			curl_close( $ch );
			throw new RealexException( "Exception communicating with Realex" );
		}

		curl_close( $ch );

		$response = new HttpResponse();
		$response->setResponseCode( $statusCode );
		$response->setBody( $responseXml );

		return $response;
	}

	/**
	 * Getter for connectTimeout
	 *
	 * @return int
	 */
	public function getConnectTimeout() {
		return $this->connectTimeout;
	}

	/**
	 * Setter for connectTimeout
	 *
	 * @param int $connectTimeout
	 */
	public function setConnectTimeout( $connectTimeout ) {
		$this->connectTimeout = $connectTimeout;
	}

	/**
	 * Getter for socketTimeout
	 *
	 * @return int
	 */
	public function getSocketTimeout() {
		return $this->socketTimeout;
	}

	/**
	 * Setter for socketTimeout
	 *
	 * @param int $socketTimeout
	 */
	public function setSocketTimeout( $socketTimeout ) {
		$this->socketTimeout = $socketTimeout;
	}
}