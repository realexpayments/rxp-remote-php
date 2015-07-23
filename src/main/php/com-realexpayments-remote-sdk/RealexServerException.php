<?php


namespace com\realexpayments\remote\sdk;


/**
 * This exception will be thrown when an error occurs on the Realex server when attempting to process
 * the request.
 *
 * @author vicpada
 */
class RealexServerException extends RealexException {

	const serialVersionUID = - 298850091427275465;

	/**
	 * @var string The error code returned from Realex
	 */
	private $errorCode;

	/**
	 * @var string The order ID of the Request/Response
	 */
	private $orderId;


	/**
	 * @var string The timestamp of the Request/Response
	 */
	private $timeStamp;

	/**
	 * RealexServerException constructor.
	 *
	 * @param string $timeStamp
	 * @param string $orderId
	 * @param string $errorCode
	 * @param string $message
	 */
	public function __construct( $timeStamp, $orderId, $errorCode, $message ) {
		parent::__construct( $message );

		$this->errorCode = $errorCode;
		$this->orderId   = $orderId;
		$this->timeStamp = $timeStamp;
	}

	/**
	 * Getter for error code.
	 *
	 * @return string error code
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}

	/**
	 * Get the order Id of the request which generated this exception.
	 *
	 * @return string order id
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * Get the timestamp of the request which generated this exception.
	 *
	 * @return string timeStamp
	 */
	public function getTimeStamp() {
		return $this->timeStamp;
	}
}