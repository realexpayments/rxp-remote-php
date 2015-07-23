<?php


namespace com\realexpayments\remote\sdk;

use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\http\HttpClient;
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
		\Phockito::include_hamcrest( false );
	}

	public function testSend() {

		//get sample response XML
		$path            = SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH;
		$prefix          = __DIR__ . '/../../resources';
		$xml             = file_get_contents( $prefix . $path );
		$fromXMLResponse = ( new PaymentResponse() )->fromXml( $xml );

		//mock HttpResponse
		/** @var HttpResponse $httpResponseMock */
		$httpResponseMock = Phockito::mock( HttpResponse::class );
		\Phockito::when( $httpResponseMock->getBody() )->return( $fromXMLResponse->toXML() );
		\Phockito::when( $httpResponseMock->getResponseCode() )->return( 200 );


		// create empty request
		$request = new PaymentRequest();

		$httpConfiguration = new HttpConfiguration();
		$httpConfiguration->setOnlyAllowHttps( false );

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( HttpClient::class );
		\Phockito::when( $httpClientMock->execute( \Hamcrest_Core_IsAnything::anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpClientMock, $httpConfiguration );
		$response     = $realexClient->send( $request );

		// validate response
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $response, $this );

	}


}
