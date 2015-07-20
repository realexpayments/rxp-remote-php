<?php

namespace com\realexpayments\remote\sdk;

use com\realexpayments\remote\sdk\domain\iHttpClient;
use com\realexpayments\remote\sdk\http\HttpConfiguration;
use com\realexpayments\remote\sdk\http\HttpUtils;
use Logger;

/**
 * <p>
 * Realex client class for sending requests to Realex.
 * <p>
 * <p>
 * The client class exposes three constructors which offer different HTTP configuration options.
 * </p>
 * <p>
 * <ol>
 *
 * <li>
 * A default {@link HttpClient} instance will be used for sending HTTP requests to Realex.
 * Some default configuration settings will be applied as outlined in {@link HttpConfiguration}. Example construction:
 * <code><pre>
 * $client = new RealexClient("shared secret");
 * </pre></code>
 * </li>
 *
 * <li>
 * A default {@link HttpClient} will be used, but it will be configured based on the
 * supplied {@link HttpConfiguration} object. Example construction:
 * <code><pre>
 * $httpConfiguration = new HttpConfiguration("https://epage.payandshop.com/epage.cgi", 1000);
 * $client = new RealexClient("shared secret", httpConfiguration);
 * </pre></code>
 * </li>
 *
 * <li>
 * The supplied {@link HttpClient} will be used, and will be further configured based on the
 * supplied {@link HttpConfiguration} object. Example construction:
 * <code><pre>
 * $httpConfiguration = new HttpConfiguration("https://epage.payandshop.com/epage.cgi", 1000);
 * $httpClient = createCustomHttpClient(); //custom HttpClient created by merchant
 * $client = new RealexClient("shared secret", httpClient, httpConfiguration);
 * </pre></code>
 * </li>
 *
 * </ol>
 * </p>
 * @author vicpada
 *
 */
class RealexClient {

	/**
	 * @var Logger Logger
	 */
	private $logger;


	/**
	 * The shared secret issued by Realex. Used to create the SHA-1 hash in the request and
	 * to verify the validity of the XML response.
	 *
	 * @var string secret
	 */
	private $secret;


	/**
	 * HttpClient instance
	 * @var iHttpClient httpclient
	 */
	private $httpClient;


	/**
	 * HttpConfiguration
	 * @var HttpConfiguration http configuration
	 */
	private $httpConfiguration;


	/**
	 * Realex client constructor. Will use default HTTP configuration.
	 *
	 * @param $secret string secret
	 */
	public function __construct($secret) {
		$this->logger = Logger::getLogger( __CLASS__ );
		$this->secret= $secret;
		$this->httpConfiguration = new HttpConfiguration();
		$this->httpClient = HttpUtils::getDefaultClient($this->httpConfiguration);
	}




	/**
	 * @param $request
	 */
	public function send( $request ) {
	}
}