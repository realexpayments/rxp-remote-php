<?php


namespace com\realexpayments\remote\sdk;

use com\realexpayments\remote\sdk\domain\payment\PaymentHttpResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\utils\SampleXmlValidationUtils;
use Phockito;

class RealexClientTest extends \PHPUnit_Framework_TestCase {

	public function sendTest() {

		//get sample response XML
		$file            = fopen( SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH, "r" );
		$fromXMLResponse = ( new PaymentHttpResponse() )->fromXml( $file );

		//mock HttpResponse
		$httpResponseMock = Phockito::mock( 'iHttpResponse' );
		when( $httpResponseMock->getEntity() )->return( $fromXMLResponse->toXML() );


		// create empty request
		$request = new PaymentRequest();

		// mock HttpClient instance
		$httpClientMock = Phockito::mock( 'iHttpClient' );
		when( $httpClientMock->execute( anything() ) )->return( $httpResponseMock );

		// execute and send on client
		$realexClient = new RealexClient( SampleXmlValidationUtils::SECRET, $httpClientMock );
		$response     = $realexClient->send( $request );

		// validate response
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $response, $this );

	}


}
