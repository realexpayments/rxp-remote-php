<?php


namespace com\realexpayments\remote\sdk\http;


use com\realexpayments\remote\sdk\domain\iHttpResponse;

class HttpResponse implements iHttpResponse {

	/**
	 * @var string entity
	 */
	private $entity;

	private $statusCode;
	private $statusReason;

	/**
	 * @return string entity
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 * @param string $entity
	 */
	public function setEntity( $entity ) {
		$this->entity = $entity;
	}


	/**
	 * Constructor of HttpResponse
	 *
	 * @param $statusCode string
	 * @param $statusReason string
	 */
	public function  __constructor( $statusCode, $statusReason ) {
		$this->statusCode   = $statusCode;
		$this->statusReason = $statusReason;
	}

}