<?php


namespace com\realexpayments\remote\sdk\http;


/**
 * Object containing all configurable HTTP settings.
 */
/**
 * Class HttpConfiguration
 * @package com\realexpayments\remote\sdk\http
 */
class HttpConfiguration {


	/**
	 * The default endpoint for all requests
	 */
	const DEFAULT_ENDPOINT = "https://api.realexpayments.com/epage-remote.cgi";

	/**
	 * The default timeout in milliseconds.
	 */
	const DEFAULT_TIMEOUT = 65000;

	/** The URL of the Realex service. */
	private $endpoint;

	/** The timeout, in milli-seconds, for sending a request to Realex. */
	private $timeout;

	/** Whether only HTTPS is allowed for the endpoint. */
	private $onlyAllowHttps = true;


	/**
	 * Create a HttpConfiguration object with all defaults in place.
	 *
	 */
	public function  __construct() {
		// Set defaults
		$this->endpoint = $this::DEFAULT_ENDPOINT;
		$this->timeout  = $this::DEFAULT_TIMEOUT;
	}

	/**
	 * Get the endpoint/destination for the request.
	 *
	 * @return string the end point
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}

	/**
	 * Set the endpoint/destination for the request.
	 *
	 * @param string $endpoint
	 */
	public function setEndpoint( $endpoint ) {
		$this->endpoint = $endpoint;
	}

	/**
	 * The timeout for a request to Realex.
	 *
	 * @return int timeout
	 */
	public function getTimeout() {
		return $this->timeout;
	}

	/**
	 * Set the timeout, in milli-seconds, for sending a request to Realex.
	 *
	 * @param int $timeout
	 */
	public function setTimeout( $timeout ) {
		$this->timeout = $timeout;
	}

	/**
	 * Check is HTTPS the only allowed scheme (protocol) to the endpoint.
	 *
	 * @return bool onlyAllowHttps
	 */
	public function isOnlyAllowHttps() {
		return $this->onlyAllowHttps;
	}

	/**
	 * Set whether (true) or not (false) HTTPS is the only allowed scheme (protocol) to the endpoint.
	 *
	 * @param bool $onlyAllowHttps  the onlyAllowHttps to set
	 */
	public function setOnlyAllowHttps( $onlyAllowHttps ) {
		$this->onlyAllowHttps = $onlyAllowHttps;
	}

	/**
	 * Helper method for adding a endpoint
	 *
	 * @param string $endpoint
	 *
	 * @return HttpConfiguration
	 */
	public function addEndpoint( $endpoint ) {
		$this->endpoint = $endpoint;

		return $this;
	}

	/**
	 * Helper method for adding a timeout
	 *
	 * @param int $timeout
	 *
	 * @return HttpConfiguration
	 */
	public function addTimeout( $timeout ) {
		$this->timeout = $timeout;

		return $this;
	}

	/**
	 * Helper method for adding a onlyAllowHttps
	 *
	 * @param bool $onlyAllowHttps
	 *
	 * @return HttpConfiguration
	 */
	public function addOnlyAllowHttps( $onlyAllowHttps ) {
		$this->onlyAllowHttps = $onlyAllowHttps;

		return $this;
	}
}