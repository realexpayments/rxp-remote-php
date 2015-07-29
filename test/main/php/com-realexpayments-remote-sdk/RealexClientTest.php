<?php


namespace com\realexpayments\remote\sdk;

use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureRequest;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureResponse;
use com\realexpayments\remote\sdk\http\HttpConfiguration;
use com\realexpayments\remote\sdk\http\HttpResponse;
use com\realexpayments\remote\sdk\utils\SampleXmlValidationUtils;
use Phockito;

class RealexClientTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, open a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		Phockito::include_hamcrest( false );
	}

	/**
	 * Test sending a payment request and receiving a payment response.
	 */
	public function testSend() {

		//get sample response XML
		$path            = SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH;
		$prefix          = __DIR__ . '/../../resources';
		$xml             = file_get_contents( $prefix . $path );
		$fromXMLResponse = new PaymentResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new PaymentRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );
		$response     = $realexClient->send( $request );

		// validate response
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $response, $this );
	}

	/**
	 * Test sending a payment request and receiving a payment response error.
	 */
	public function  testSendWithShortErrorResponse() {

		//get sample response XML
		$path            = SampleXmlValidationUtils::PAYMENT_RESPONSE_BASIC_ERROR_XML_PATH;
		$prefix          = __DIR__ . '/../../resources';
		$xml             = file_get_contents( $prefix . $path );
		$fromXMLResponse = new PaymentResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new PaymentRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );

		try {
			$realexClient->send( $request );
			$this->fail( "RealexException should have been thrown before this point." );
		} catch ( RealexServerException $e ) {
			//validate error
			SampleXmlValidationUtils::checkBasicResponseError( $e, $this );
		}

	}


	/**
	 * Test sending a payment request and receiving a payment response error.
	 */
	public function  testSendWithLongErrorResponse() {

		//get sample response XML
		$path            = SampleXmlValidationUtils::PAYMENT_RESPONSE_FULL_ERROR_XML_PATH;
		$prefix          = __DIR__ . '/../../resources';
		$xml             = file_get_contents( $prefix . $path );
		$fromXMLResponse = new PaymentResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new PaymentRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );


		$response = $realexClient->send( $request );

		//validate error
		SampleXmlValidationUtils::checkFullResponseError( $response, $this );

	}


	/**
	 * Test sending a payment request and receiving a payment response error.
	 */
	public function  testSendWithErrorResponseInvalidCode() {

		//get sample response XML
		$path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_BASIC_ERROR_XML_PATH;
		$prefix = __DIR__ . '/../../resources';
		$xml    = file_get_contents( $prefix . $path );

		/** @var PaymentResponse $fromXMLResponse */
		$fromXMLResponse = new PaymentResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );
		$fromXMLResponse->setResult( "invalid" );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new PaymentRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );


		$correctExceptionThrown = false;

		try {
			$realexClient->send( $request );
			$this->fail( "RealexException should have been thrown before this point." );

		} catch ( RealexServerException $e ) {
			$this->fail( "Incorrect exception thrown. Expected RealexException as result code is invalid." );
		} catch ( RealexException $e ) {
			$correctExceptionThrown = true;
		}

		$this->assertTrue( $correctExceptionThrown, "Incorrect exception thrown." );
	}


	/**
	 * Test receiving a response which has an invalid hash.
	 *
	 * @expectedException com\realexpayments\remote\sdk\RealexException
	 */
	public function  testSendInvalidResponseHash() {

		//get sample response XML
		$path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH;
		$prefix = __DIR__ . '/../../resources';
		$xml    = file_get_contents( $prefix . $path );

		/** @var PaymentResponse $fromXMLResponse */
		$fromXMLResponse = new PaymentResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );

		//add invalid hash
		$fromXMLResponse->setHash( "invalid hash" );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new PaymentRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );
		$realexClient->send( $request );

		//shouldn't get this far
		$this->fail( "RealexException should have been thrown before this point." );
	}

	/**
	 * Test sending a ThreeDSecure Verify Enrolled request and receiving a ThreeDSecure Verify Enrolled response.
	 */
	public function testSendThreeDSecureVerifyEnrolled()
	{
		//get sample response XML
		$path            = SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_RESPONSE_XML_PATH;
		$prefix          = __DIR__ . '/../../resources';
		$xml             = file_get_contents( $prefix . $path );
		$fromXMLResponse = new ThreeDSecureResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new ThreeDSecureRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );
		$response     = $realexClient->send( $request );

		// validate response
		SampleXmlValidationUtils::checkUnmarshalledThreeDSecureEnrolledResponse( $response, $this );
	}

	/**
	 * Test sending a ThreeDSecure Verify Enrolled request and receiving a ThreeDSecure Verify Enrolled response.
	 *
	 * @expectedException com\realexpayments\remote\sdk\RealexException I
	 */
	public function testSendThreeDSecureInvalidResponseHash()
	{
		//get sample response XML
		$path            = SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_RESPONSE_XML_PATH;
		$prefix          = __DIR__ . '/../../resources';
		$xml             = file_get_contents( $prefix . $path );
		$fromXMLResponse = new ThreeDSecureResponse();
		$fromXMLResponse = $fromXMLResponse->fromXml( $xml );

		// add invalid hash
		$fromXMLResponse->setHash("invalid hash");

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpResponse" );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new ThreeDSecureRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( "com\\realexpayments\\remote\\sdk\\http\\HttpClient" );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpConfiguration, $httpClientMock );
		$realexClient->send( $request );

		$this->fail("RealexException should have been thrown before this point.");
	}


}
