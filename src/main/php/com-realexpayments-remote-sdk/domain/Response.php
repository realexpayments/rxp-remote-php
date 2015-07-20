<?php


namespace com\realexpayments\remote\sdk\domain;


interface iResponse {

	const RESULT_CODE_SUCCESS = "00";
	const RESULT_CODE_PREFIX_ERROR_RESPONSE_START = 3;


	/**
	 * @param $resource
	 *
	 * @return iResponse
	 */
	public function fromXML(  $resource );


	/**
	 * @return string
	 */
	public function toXML();


	/**
	 * @param $secret
	 *
	 * @return bool
	 */
	public function isHashValid($secret);


	public function getResult();

	public function getMessage();

	public function  getOrderId();

	public function getTimeStamp();

	public function isSuccess();

}