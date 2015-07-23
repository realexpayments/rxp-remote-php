<?php


namespace com\realexpayments\remote\sdk\http;


/**
 * Class HttpClient. Wrapper for curl calls
 * @package com\realexpayments\remote\sdk\http
 */
class HttpClient {

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
	}

	/**
	 * @param HttpRequest $httpRequest
	 *
	 * @return HttpResponse
	 */
	public function execute($httpRequest) {

		return new HttpResponse();
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