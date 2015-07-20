<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Class iHttpClient
 * @package com\realexpayments\remote\sdk\domain
 */
interface iHttpClient {

	/**
	 * @param $url string Url to execute
	 * @return iHttpResponse
	 */
	public function execute($url);

}