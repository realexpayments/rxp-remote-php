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

	/**
	 * Tests the hash calculation for a verify card transaction.
	 */
	public function testVerifyCardEnrolledHashGeneration() {

		$request = new ThreeDSecureRequest();
		$request->addType( ThreeDSecureType::VERIFY_CARD_ENROLLED )
		        ->addTimeStamp( SampleXmlValidationUtils::CARD_VERIFY_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::CARD_VERIFY_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::CARD_VERIFY_ORDER_ID )
		        ->addAmount( SampleXmlValidationUtils::CARD_VERIFY_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::CARD_VERIFY_CURRENCY )
		        ->addPayerReference( SampleXmlValidationUtils::CARD_VERIFY_PAYER_REF );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::CARD_VERIFY_REQUEST_HASH, $request->getHash() );
	}

}