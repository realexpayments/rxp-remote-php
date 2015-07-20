<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Interface iHttpResponse
 * @package com\realexpayments\remote\sdk\domain
 */
interface iHttpResponse {

	/**
	 * @return string
	 */
	public function getEntity();


	/**
	 * @return string Account
	 */
	public function getAccount();
}