<?php


namespace com\realexpayments\remote\sdk\http;

use com\realexpayments\remote\sdk\RealexException;
use Exception;


/**
 * HTTP Utils such as getting a default http client and sending a message.
 * @author vicpada
 */
class HttpUtilsTest extends \PHPUnit_Framework_TestCase {


	/**
	 * Test sending a message, expecting a successful response.
	 */
	public function testSendMessageSuccess() {

		\Phockito::include_hamcrest( false );

		try {
			$endpoint       = 'https://some-test-endpoint';
			$onlyAllowHttps = true;

			$xml = "<element>test response xml</element>";

			// Dummy and Mock required objects
			$statusCode = 200;

			$httpResponse = new HttpResponse();
			$httpResponse->setResponseCode( $statusCode );
			$httpResponse->setBody( $xml );

			/** @var HttpConfiguration $configurationMock */
			$configurationMock = \Phockito::mock( HttpConfiguration::class );
			\Phockito::when( $configurationMock->getEndPoint() )->return( $endpoint );
			\Phockito::when( $configurationMock->isOnlyAllowHttps() )->return( $onlyAllowHttps );

			/** @var HttpClient $httpClientMock */
			$httpClientMock = \Phockito::mock( HttpClient::class );

			/** @var HttpRequest $anything */
			\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponse );

			// execute the method
			$response = HttpUtils::sendMessage( $xml, $httpClientMock, $configurationMock );

			// check the response
			$this->assertEquals( $response, $xml );


		} catch ( Exception $e ) {
			$this->fail( "Unexpected exception:" . $e->getMessage() );
		}
	}

	/**
	 * Test sending a message, expecting an exception due to failure response.
	 */
	public function testSendMessageFailure() {
		// Dummy and Mock required objects
		$statusCode = 400;

		$this->setExpectedException( RealexException::class, "Unexpected http status code [" . $statusCode . "]" );

		try {
			$endpoint       = 'https://some-test-endpoint';
			$onlyAllowHttps = true;

			$xml = "<element>test response xml</element>";

			$httpResponse = new HttpResponse();
			$httpResponse->setResponseCode( $statusCode );
			$httpResponse->setBody( $xml );

			/** @var HttpConfiguration $configurationMock */
			$configurationMock = \Phockito::mock( HttpConfiguration::class );
			\Phockito::when( $configurationMock->getEndPoint() )->return( $endpoint );
			\Phockito::when( $configurationMock->isOnlyAllowHttps() )->return( $onlyAllowHttps );

			/** @var HttpClient $httpClientMock */
			$httpClientMock = \Phockito::mock( HttpClient::class );

			/** @var HttpRequest $anything */
			\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponse );

			// execute the method
			$response = HttpUtils::sendMessage( $xml, $httpClientMock, $configurationMock );
		} catch ( RealexException $e ) {
			throw $e;
		} catch ( Exception $e ) {
			$this->fail( "Unexpected exception:" . $e->getMessage() );
		}

	}

	/**
	 * Test sending a message, expecting an exception due to failure response.
	 *
	 */
	public function testSendMessageFailureHttpNotAllowed() {
		// Dummy and Mock required objects
		$statusCode = 200;

		$this->setExpectedException( RealexException::class, "Protocol must be https" );

		try {
			$endpoint       = 'http://some-test-endpoint';
			$onlyAllowHttps = true;

			$xml = "<element>test response xml</element>";

			$httpResponse = new HttpResponse();
			$httpResponse->setResponseCode( $statusCode );
			$httpResponse->setBody( $xml );

			/** @var HttpConfiguration $configurationMock */
			$configurationMock = \Phockito::mock( HttpConfiguration::class );
			\Phockito::when( $configurationMock->getEndPoint() )->return( $endpoint );
			\Phockito::when( $configurationMock->isOnlyAllowHttps() )->return( $onlyAllowHttps );

			/** @var HttpClient $httpClientMock */
			$httpClientMock = \Phockito::mock( HttpClient::class );

			/** @var HttpRequest $anything */
			\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponse );

			// execute the method
			$response = HttpUtils::sendMessage( $xml, $httpClientMock, $configurationMock );
		} catch ( RealexException $e ) {
			throw $e;
		} catch ( Exception $e ) {
			$this->fail( "Unexpected exception:" . $e->getMessage() );
		}


	}

}
