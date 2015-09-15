<?php


namespace com\realexpayments\remote\sdk\utils;
use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;


/**
 * Unit test class for {@link ResponseUtils}.
 *
 * @author vicpada
 *
 */
class ResponseUtilsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if the response is a basic response.
	 */
	public  function  testIsBasicResponse()
	{

		//test success response
		$this->assertFalse(ResponseUtils::isBasicResponse(iResponse::RESULT_CODE_SUCCESS));

		//test 1xx code
		$this->assertFalse(ResponseUtils::isBasicResponse("100"));

		//test 2xx code
		$this->assertFalse(ResponseUtils::isBasicResponse("200"));

		//test 3xx code
		$this->assertTrue(ResponseUtils::isBasicResponse("300"));

		//test 5xx code
		$this->assertTrue(ResponseUtils::isBasicResponse("500"));
	}

	/**
	 * Test if the full response is successful.
	 */
	public function testIsSuccess(){
		$response = new PaymentResponse();

		// test success
		$response->setResult(iResponse::RESULT_CODE_SUCCESS);
		$this->assertTrue(ResponseUtils::isSuccess($response));

		// test failure
		$response->setResult("101");
		$this->assertFalse(ResponseUtils::isSuccess($response));


	}
}
