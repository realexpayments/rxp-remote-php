<?php


namespace com\realexpayments\remote\sdk\http;
use com\realexpayments\remote\sdk\domain\iHttpClient;
use Exception;


/**
 * HTTP Utils such as getting a default http client and sending a message.
 * @author vicpada
 */
class HttpUtilsTest extends \PHPUnit_Framework_TestCase {


	/**
	 * Test sending a message, expecting a successful response.
	 */
	public function sendMessageSuccessTest() {
		try {
			$endpoint  = 'https://some-test-endpoint';
			$onlyAllowHttps = true;

			$xml = "<element>test response xml</element>";

			// Dummy and Mock required objects
			$statusCode = 200;
			$statusReason = "";

			$httpResponse = new HttpResponse($statusCode,$statusReason);
			$httpResponse->setEntity($xml);

			$configurationMock = \Phockito::mock(HttpConfiguration::class);
			when($configurationMock->getEndPoint())->then($endpoint);
			when($configurationMock->isOnlyAllowHttps)->then($onlyAllowHttps);

			$httpClientMock = \Phockito::mock(iHttpClient::class);
			when($httpClientMock->execute(anything()))->return($httpResponse);

			// execute the method
			$response = HttpUtils::sendMessage($xml,$httpClientMock,$configurationMock);

			// check the response
			$this->assertEquals($response,$xml);


		} catch ( Exception $e ) {
			$this->fail("Unexpected exception:" + $e->getMessage());
		}
	}

}
