<?php


namespace com\realexpayments\remote\sdk\http;


/**
 * Class HttpResponse
 * @package com\realexpayments\remote\sdk\http
 */
class HttpResponse {

	/**
	 * @var string
	 */
	private $responseCode;

	/**
	 * @var string
	 */
	private $body;


	/**
	 * HttpResponse constructor.
	 */
	public function __construct() {
	}


	/**
	 * Getter for responseCode
	 *
	 * @return string
	 */
	public function getResponseCode() {
		return $this->responseCode;
	}

	/**
	 * Setter for responseCode
	 *
	 * @param string $responseCode
	 */
	public function setResponseCode( $responseCode ) {
		$this->responseCode = $responseCode;
	}

	/**
	 * Getter for body
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Setter for body
	 *
	 * @param string $body
	 */
	public function setBody( $body ) {
		$this->body = $body;
	}
}