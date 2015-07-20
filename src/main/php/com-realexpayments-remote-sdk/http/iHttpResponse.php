<?php


namespace com\realexpayments\remote\sdk\domain;


/**
 * Interface iHttpResponse
 * @package com\realexpayments\remote\sdk\domain
 */
interface iHttpResponse {

	/**
	 * @return string entity
	 */
	public function getEntity();

	/**
	 * @param string $entity
	 */
	public function setEntity($entity);



}