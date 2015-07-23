<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\iResponse;

/**
 * Utils class offering methods which act on the Realex response.
 *
 * @package com\realexpayments\remote\sdk\utils
 * @author vicpada
 */
class ResponseUtils {

	/**
	 * Returns <code>true</code> if the response contains a result code representing success.
	 *
	 * @param iResponse $response
	 * @return bool
	 */
	public static function isSuccess(iResponse $response ) {

		return iResponse::RESULT_CODE_SUCCESS == $response->getResult();
	}
}