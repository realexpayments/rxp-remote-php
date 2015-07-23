<?php


namespace com\realexpayments\remote\sdk\http;


class HttpClient {

	/**
	 * HttpClient constructor.
	 */
	public function __construct() {
	}

	/**
	 * @param HttpRequest $httpRequest
	 *
	 * @return HttpResponse
	 */
	public function execute($httpRequest) {

		return new HttpResponse();
	}
}