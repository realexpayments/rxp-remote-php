<?php

namespace com\realexpayments\remote\sdk;

use com\realexpayments\remote\sdk\domain\iRequest;
use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\http\HttpClient;
use com\realexpayments\remote\sdk\http\HttpConfiguration;
use com\realexpayments\remote\sdk\http\HttpUtils;
use com\realexpayments\remote\sdk\utils\ResponseUtils;
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
	 * @var string The shared secret issued by Realex. Used to create the SHA-1 hash in the request and
	 * to verify the validity of the XML response.
	 */
	private $secret;


	/**
	 * @var HttpClient HttpClient instance
	 */
	private $httpClient;


	/**
	 * HttpConfiguration
	 * @var HttpConfiguration http configuration
	 */
	private $httpConfiguration;

	/**
	 * RealexClient constructor.
	 *
	 * @param string $secret
	 * @param HttpClient $httpClient
	 * @param HttpConfiguration $httpConfiguration
	 */
	public function __construct( $secret, HttpConfiguration $httpConfiguration = null, HttpClient $httpClient = null ) {
		$this->logger = RXPLogger::getLogger( __CLASS__ );

		$this->secret = $secret;

		if ( is_null( $httpConfiguration ) ) {
			$this->httpConfiguration = new HttpConfiguration();
		} else {
			$this->httpConfiguration = $httpConfiguration;
		}

		if ( is_null( $httpClient ) ) {
			$this->httpClient = HttpUtils::getDefaultClient( $this->httpConfiguration );
		} else {
			$this->httpClient = $httpClient;
		}
	}

	/**
	 * Getter for secret
	 *
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * Setter for secret
	 *
	 * @param string $secret
	 */
	public function setSecret( $secret ) {
		$this->secret = $secret;
	}

	/**
	 * Getter for httpClient
	 *
	 * @return HttpClient
	 */
	public function getHttpClient() {
		return $this->httpClient;
	}

	/**
	 * Setter for httpClient
	 *
	 * @param HttpClient $httpClient
	 */
	public function setHttpClient( $httpClient ) {
		$this->httpClient = $httpClient;
	}

	/**
	 * Getter for httpConfiguration
	 *
	 * @return HttpConfiguration
	 */
	public function getHttpConfiguration() {
		return $this->httpConfiguration;
	}

	/**
	 * Setter for httpConfiguration
	 *
	 * @param HttpConfiguration $httpConfiguration
	 */
	public function setHttpConfiguration( $httpConfiguration ) {
		$this->httpConfiguration = $httpConfiguration;
	}


	/**
	 * <p>
	 * Sends the request to Realex. Actions:
	 *
	 * <ol>
	 * <li>Generates any defaults on the request e.g. hash, time stamp order ID.</li>
	 * <li>Marshals request to XML.</li>
	 * <li>Sends request to Realex.</li>
	 * <li>Unmarshals response.</li>
	 * <li>Checks result code (If response is an error then throws {@link RealexServerException}).</li>
	 * <li>Validates response hash (If invalid throws {@link RealexException}).</li>
	 * </ol>
	 * </p>
	 *
	 * @param iRequest $request
	 *
	 * @return iResponse
	 */
	public function send( iRequest $request ) {

		$this->logger->info( "Sending XML request to Realex." );

		//generate any required defaults e.g. order ID, time stamp, hash
		$request->generateDefaults( $this->secret );

		//convert request to XML
		$this->logger->debug( "Marshalling request object to XML." );
		$xmlRequest = $request->toXml();

		//send request to Realex.
		$xmlResult = HttpUtils::sendMessage( $xmlRequest, $this->httpClient, $this->httpConfiguration );

		//log the response
		$this->logger->trace( "Response XML from server: " . $xmlResult );

		//convert XML to response object
		$this->logger->debug( "Unmarshalling XML to response object." );
		$response = $request->responseFromXml( $xmlResult );

		//throw exception if short response returned (indicating request could not be processed).
		if ( ResponseUtils::isBasicResponse( $response->getResult() ) ) {
			$this->logger->error( "Error response received from Realex with code " . $response->getResult() . " and message " . $response->getMessage() . "." );
			throw new RealexServerException( $response->getTimeStamp(), $response->getOrderId(), $response->getResult(), $response->getMessage() );
		}

		//validate response hash
		$this->logger->debug( "Verifying response hash." );

		if ( ! $response->isHashValid( $this->secret ) ) {
			//Hash invalid. Throw exception.
			$this->logger->error( "Response hash is invalid. This response's validity cannot be verified." );
			throw new RealexException( "Response hash is invalid. This response's validity cannot be verified." );
		}

		return $response;

	}
}