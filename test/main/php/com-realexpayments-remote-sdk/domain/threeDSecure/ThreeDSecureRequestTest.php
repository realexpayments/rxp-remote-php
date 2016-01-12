<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure;

use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\utils\SampleXmlValidationUtils;

/**
 * Unit test class for {@link ThreeDSecureRequest} utility methods.
 *
 * @package com\realexpayments\remote\sdk\domain\threeDSecure
 * @author vicpada
 */
class ThreeDSecureRequestTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test hash calculation for 3DSecure Verify Enrolled
	 */
	public function testVerifyEnrolledHashGeneration() {

		$card = new Card();

		$request = new ThreeDSecureRequest();
		$request->addType( ThreeDSecureType::VERIFY_ENROLLED )
		        ->addTimeStamp( SampleXmlValidationUtils::TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::ORDER_ID )
		        ->addAmount( SampleXmlValidationUtils::AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::CURRENCY )
		        ->addCard( $card->addNumber( SampleXmlValidationUtils::CARD_NUMBER ) );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for an auth payment.
	 */
	public function testVerifySigHashGeneration() {

		$card = new Card();

		$request = new ThreeDSecureRequest();
		$request->addType( ThreeDSecureType::VERIFY_SIG )
		        ->addTimeStamp( SampleXmlValidationUtils::TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::ORDER_ID )
		        ->addAmount( SampleXmlValidationUtils::AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::CURRENCY )
		        ->addCard( $card->addNumber( SampleXmlValidationUtils::CARD_NUMBER ) );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::REQUEST_HASH, $request->getHash() );
	}

}