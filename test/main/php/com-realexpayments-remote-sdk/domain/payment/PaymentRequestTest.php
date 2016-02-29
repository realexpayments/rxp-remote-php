<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\utils\SampleXmlValidationUtils;


/**
 * Unit test class for PaymentRequest utility methods.
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 */
class PaymentRequestTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests the population of a billing address for the Address Verification Service.
	 */
	public function testAddAddressVerificationServiceDetails1() {
		//test variations of address and postcode with TSS Info field null
		$addressLine         = "123 Fake St";
		$postcode            = "WB1 A42";
		$country             = "GB";
		$expectedBillingCode = "142|123";
		$request             = new PaymentRequest();
		$request->addAddressVerificationServiceDetails( $addressLine, $postcode, $country );

		$addresses = $request->getTssInfo()->getAddresses();

		$this->assertEquals( $expectedBillingCode, $addresses[0]->getCode() );
		$this->assertEquals( AddressType::BILLING, $addresses[0]->getType() );
		$this->assertEquals( $country, $addresses[0]->getCountry() );
	}

	/**
	 * Tests the population of a billing address for the Address Verification Service.
	 */
	public function testAddAddressVerificationServiceDetails2() {
		$addressLine         = "123 5 Fake St";
		$postcode            = "1WB 5A2";
		$country             = "GB";
		$expectedBillingCode = "152|1235";
		$request             = new PaymentRequest();
		$request->addAddressVerificationServiceDetails( $addressLine, $postcode, $country );

		$addresses = $request->getTssInfo()->getAddresses();
		$this->assertEquals( $expectedBillingCode, $addresses[0]->getCode() );
		$this->assertEquals( AddressType::BILLING, $addresses[0]->getType() );
		$this->assertEquals( $country, $addresses[0]->getCountry() );
	}

	/**
	 * Tests the population of a billing address for the Address Verification Service.
	 */
	public function testAddAddressVerificationServiceDetails3() {
		$addressLine         = "Apt 15, 123 Fake St";
		$postcode            = "ABC 5A2";
		$country             = "GB";
		$expectedBillingCode = "52|15123";
		$request             = new PaymentRequest();
		$request->addAddressVerificationServiceDetails( $addressLine, $postcode, $country );

		$addresses = $request->getTssInfo()->getAddresses();
		$this->assertEquals( $expectedBillingCode, $addresses[0]->getCode() );
		$this->assertEquals( AddressType::BILLING, $addresses[0]->getType() );
		$this->assertEquals( $country, $addresses[0]->getCountry() );
	}

	/**
	 * Tests the population of a billing address for the Address Verification Service.
	 */
	public function testAddAddressVerificationServiceDetails4() {
		$addressLine         = "Fake St";
		$postcode            = "AI10 9AB";
		$country             = "GB";
		$expectedBillingCode = "109|";
		$request             = new PaymentRequest();
		$request->addAddressVerificationServiceDetails( $addressLine, $postcode, $country );

		$addresses = $request->getTssInfo()->getAddresses();
		$this->assertEquals( $expectedBillingCode, $addresses[0]->getCode() );
		$this->assertEquals( AddressType::BILLING, $addresses[0]->getType() );
		$this->assertEquals( $country, $addresses[0]->getCountry() );
	}


	/**
	 * Tests the population of a billing address for the Address Verification Service.
	 */
	public function testAddAddressVerificationServiceDetails5() {
		$addressLine         = "30 Fake St";
		$postcode            = "";
		$country             = "GB";
		$expectedBillingCode = "|30";
		$request             = new PaymentRequest();
		$request->addAddressVerificationServiceDetails( $addressLine, $postcode, $country );

		$addresses = $request->getTssInfo()->getAddresses();
		$this->assertEquals( $expectedBillingCode, $addresses[0]->getCode() );
		$this->assertEquals( AddressType::BILLING, $addresses[0]->getType() );
		$this->assertEquals( $country, $addresses[0]->getCountry() );
	}


	/**
	 * Tests the population of a billing address for the Address Verification Service.
	 */
	public function testAddAddressVerificationServiceDetailsWithTssInfo() {
		$addressLine         = "123 Fake St";
		$postcode            = "WB1 A42";
		$country             = "";
		$expectedBillingCode = "142|123";
		$request             = new PaymentRequest();
		$request->addTssInfo( new TssInfo() )
		        ->addAddressVerificationServiceDetails( $addressLine, $postcode, $country );

		$addresses = $request->getTssInfo()->getAddresses();
		$this->assertEquals( $expectedBillingCode, $addresses[0]->getCode() );
		$this->assertEquals( AddressType::BILLING, $addresses[0]->getType() );
		$this->assertEquals( $country, $addresses[0]->getCountry() );
	}

	/**
	 * Tests the hash calculation for an auth payment.
	 */
	public function testAuthHashGeneration() {

		$card = new Card();

		$request = new PaymentRequest();
		$request->addType( PaymentType::AUTH )
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
	public function testAuthMobileHashGeneration() {

		$request = new PaymentRequest();
		$request->addType( PaymentType::AUTH_MOBILE )
		        ->addTimeStamp( SampleXmlValidationUtils::AUTH_MOBILE_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::AUTH_MOBILE_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::AUTH_MOBILE_ORDER_ID )
		        ->addToken( SampleXmlValidationUtils::AUTH_MOBILE_TOKEN );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::AUTH_MOBILE_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a settle payment->
	 */
	public function testSettleHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::SETTLE )->addTimeStamp( SampleXmlValidationUtils::SETTLE_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::SETTLE_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::SETTLE_ORDER_ID )->addPaymentsReference( SampleXmlValidationUtils::SETTLE_PASREF )->addAuthCode( SampleXmlValidationUtils::SETTLE_AUTH_CODE )
		        ->addAmount( SampleXmlValidationUtils::SETTLE_AMOUNT )->addCurrency( SampleXmlValidationUtils::SETTLE_CURRENCY );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::SETTLE_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a void payment->
	 */
	public function testVoidHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::VOID )->addTimeStamp( SampleXmlValidationUtils::VOID_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::VOID_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::VOID_ORDER_ID )->addPaymentsReference( SampleXmlValidationUtils::VOID_PASREF )->addAuthCode( SampleXmlValidationUtils::VOID_AUTH_CODE );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::VOID_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a rebate payment->
	 */
	public function testRebateHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::REBATE )->addTimeStamp( SampleXmlValidationUtils::REBATE_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::REBATE_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::REBATE_ORDER_ID )->addPaymentsReference( SampleXmlValidationUtils::REBATE_PASREF )->addAuthCode( SampleXmlValidationUtils::REBATE_AUTH_CODE )
		        ->addAmount( SampleXmlValidationUtils::REBATE_AMOUNT )->addCurrency( SampleXmlValidationUtils::REBATE_CURRENCY )->addRefundHash( SampleXmlValidationUtils::REBATE_REFUND_HASH );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::REBATE_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for an OTB request->
	 */
	public function testOTBHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::OTB )->addTimeStamp( SampleXmlValidationUtils::OTB_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::OTB_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::OTB_ORDER_ID );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::OTB_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a credit payment->
	 */
	public function testCreditHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::CREDIT )->addTimeStamp( SampleXmlValidationUtils::CREDIT_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::CREDIT_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::CREDIT_ORDER_ID )->addPaymentsReference( SampleXmlValidationUtils::CREDIT_PASREF )->addAuthCode( SampleXmlValidationUtils::CREDIT_AUTH_CODE )
		        ->addAmount( SampleXmlValidationUtils::CREDIT_AMOUNT )->addCurrency( SampleXmlValidationUtils::CREDIT_CURRENCY )->addRefundHash( SampleXmlValidationUtils::CREDIT_REFUND_HASH );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::CREDIT_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a hold payment->
	 */
	public function testHoldHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::HOLD )->addTimeStamp( SampleXmlValidationUtils::HOLD_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::HOLD_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::HOLD_ORDER_ID )->addPaymentsReference( SampleXmlValidationUtils::HOLD_PASREF );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::HOLD_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a release payment->
	 */
	public function testReleaseHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::RELEASE )->addTimeStamp( SampleXmlValidationUtils::RELEASE_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::RELEASE_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::RELEASE_ORDER_ID )->addPaymentsReference( SampleXmlValidationUtils::RELEASE_PASREF );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::RELEASE_REQUEST_HASH, $request->getHash() );
	}


}
