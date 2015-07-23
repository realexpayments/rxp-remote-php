<?php


namespace com\realexpayments\remote\sdk\http;


/**
 * Class HttpRequest
 * @package com\realexpayments\remote\sdk\http
 */
class HttpRequest {

	/**
	 *
	 */
	const METH_POST = "POST";

	/**
	 *
	 */
	const METH_GET = "GET";


	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $method;


	/**
	 * @var string
	 */
	private $body;



	/**
	 * HttpRequest constructor.
	 *
	 * @param string $url
	 * @param string $method
	 */
	public function __construct( $url, $method ) {
		$this->url    = $url;
		$this->method = $method;
	}

	/**
	 * Getter for url
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Setter for url
	 *
	 * @param string $url
	 */
	public function setUrl( $url ) {
		$this->url = $url;
	}

	/**
	 * Getter for method
	 *
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Setter for method
	 *
	 * @param string $method
	 */
	public function setMethod( $method ) {
		$this->method = $method;
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