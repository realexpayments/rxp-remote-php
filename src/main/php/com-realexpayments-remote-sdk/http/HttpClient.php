<?php


namespace com\realexpayments\remote\sdk\http;
use com\realexpayments\remote\sdk\RPXLogger;
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
		$this->logger = RPXLogger::getLogger( __CLASS__ );
	}

	/**
	 * @param HttpRequest $httpRequest
	 *
	 * @return HttpResponse
	 */
	public function execute( $httpRequest ) {

		$url  = $httpRequest->getUrl();
		$post = $httpRequest->getMethod() == HttpRequest::METH_POST ? 1 : 0;
		$xml  = $httpRequest->getBody();

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, $post );
		curl_setopt( $ch, CURLOPT_USERAGENT, "realex sdk version 0.1" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
		/*curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); // this line makes it work under https*/
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: text/plain' ) );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout );
		curl_setopt( $ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout );

		$responseXml = curl_exec( $ch );
		$statusCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);


		curl_close( $ch );

		$response = new HttpResponse();
		$response->setResponseCode( $statusCode );
		$response->setBody($responseXml);

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