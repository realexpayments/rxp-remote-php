<?php


namespace com\realexpayments\remote\sdk;


use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\utils\SampleXmlValidationUtils;
use Phockito;

class RealexClientTest extends \PHPUnit_Framework_TestCase {

	public function sendTest() {

		//get sample response XML
		$file = fopen(SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH,"r");
		$fromXMLResponse = (new PaymentResponse())->fromXml($file);

		//mock HttpResponse
		//$httpResponseMock = $this->getMock('HttpResponse');
		//$httpResponseMock->method('xxx')->willReturn()
		$httpResponseMock= Phockito::mock('HttpResponse');




	}
}
