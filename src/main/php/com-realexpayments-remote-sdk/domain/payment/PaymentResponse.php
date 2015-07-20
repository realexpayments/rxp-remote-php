<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\iResponse;

class PaymentHttpResponse implements iResponse{

	/**
	 * @param $resource
	 *
	 * @return iResponse
	 */
	public function fromXML( $resource ) {
		// TODO: Implement fromXML() method.
	}

	/**
	 * @return string
	 */
	public function toXML() {
		// TODO: Implement toXML() method.
	}

	/**
	 * @param $secret
	 *
	 * @return bool
	 */
	public function isHashValid( $secret ) {
		// TODO: Implement isHashValid() method.
	}

	public function getResult() {
		// TODO: Implement getResult() method.
	}

	public function getMessage() {
		// TODO: Implement getMessage() method.
	}

	public function  getOrderId() {
		// TODO: Implement getOrderId() method.
	}

	public function getTimeStamp() {
		// TODO: Implement getTimeStamp() method.
	}

	public function isSuccess() {
		// TODO: Implement isSuccess() method.
	}
}