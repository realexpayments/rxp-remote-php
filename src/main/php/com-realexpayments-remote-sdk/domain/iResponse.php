<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Interface to be implemented by all classes which represent Realex responses.
 *
 * @package com\realexpayments\remote\sdk\domain
 * @author vicpada
 *
 */
interface iResponse {


	/**
	 * Defines the result code which represents success
	 */
	const RESULT_CODE_SUCCESS = "00";


	/**
	 * Realex error result codes in the range 3xx to 5xx will not return a full response message.
	 * Instead a short response will be returned with only the result code and message populated.
	 */
	const RESULT_CODE_PREFIX_ERROR_RESPONSE_START = 3;


	/**
	 * <p>
	 * Method returns a concrete implementation of the response class from an XML source.
	 * </p>
	 *
	 * @param string $resource
	 *
	 * @return iResponse
	 */
	public function fromXML(  $resource );


	/**
	 * <p>
	 * Method returns an XML representation of the interface's implementing class.
	 * </p>
	 *
	 * @return string
	 */
	public function toXML();


	/**
	 * <p>
	 * Validates the hash in the response is correct. Returns <code>true</code> if valid,
	 * <code>false</code> if not.
	 * </p>
	 *
	 * @param string $secret
	 *
	 * @return bool
	 */
	public function isHashValid($secret);


	/**
	 * Returns the result from the response.
	 *
	 * @return string
	 */
	public function getResult();

	/**
	 * Returns the message from the response.
	 *
	 * @return string
	 */
	public function getMessage();

	/**
	 * Returns the orderId from the response.
	 *
	 * @return string
	 */
	public function  getOrderId();

	/**
	 * Returns the timestamp from the response.
	 *
	 * @return string
	 */
	public function getTimeStamp();

	/**
	 * Returns <code>true</code> if response message has processed successfully.
	 *
	 * @return bool
	 */
	public function isSuccess();


}