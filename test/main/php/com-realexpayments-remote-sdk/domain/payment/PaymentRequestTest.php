<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\DccInfo;
use com\realexpayments\remote\sdk\domain\Payer;
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

	/**
	 * Tests the hash calculation for a receipt-in payment.
	 */
	public function testReceiptInHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::RECEIPT_IN )
		        ->addTimeStamp( SampleXmlValidationUtils::RECEIPT_IN_TIMESTAMP )->addMerchantId( SampleXmlValidationUtils::RECEIPT_IN_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::RECEIPT_IN_ORDER_ID )->addAmount( SampleXmlValidationUtils::RECEIPT_IN_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::RECEIPT_IN_CURRENCY )->addPayerReference( SampleXmlValidationUtils::RECEIPT_IN_PAYER );
		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::RECEIPT_IN_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a payment-out payment.
	 */
	public function testPaymentOutHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::PAYMENT_OUT )->addTimeStamp( SampleXmlValidationUtils::PAYMENT_OUT_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::PAYMENT_OUT_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::PAYMENT_OUT_ORDER_ID )
		        ->addAmount( SampleXmlValidationUtils::PAYMENT_OUT_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::PAYMENT_OUT_CURRENCY )
		        ->addPayerReference( SampleXmlValidationUtils::PAYMENT_OUT_PAYER )
		        ->addRefundHash( SampleXmlValidationUtils::PAYMENT_OUT_REFUND_HASH );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::PAYMENT_OUT_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a payer-new transaction.
	 */
	public function testPayerNewHashGeneration() {

		$payer = new Payer();
		$payer->addRef( SampleXmlValidationUtils::PAYER_NEW_PAYER_REF );

		$request = new PaymentRequest();
		$request->addType( PaymentType::PAYER_NEW )->addTimeStamp( SampleXmlValidationUtils::PAYER_NEW_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::PAYER_NEW_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::PAYER_NEW_ORDER_ID )
		        ->addPayer( $payer );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::PAYER_NEW_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a payer-edit transaction.
	 */
	public function testPayerEditHashGeneration() {

		$payer = new Payer();
		$payer->addRef( SampleXmlValidationUtils::PAYER_EDIT_PAYER_REF );

		$request = new PaymentRequest();
		$request->addType( PaymentType::PAYER_EDIT )
		        ->addTimeStamp( SampleXmlValidationUtils::PAYER_EDIT_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::PAYER_EDIT_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::PAYER_EDIT_ORDER_ID )->addPayer( $payer );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::PAYER_EDIT_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a card-new transaction.
	 */
	public function testCardNewHashGeneration() {

		$card = new Card();
		$card->addReference( SampleXmlValidationUtils::CARD_ADD_REF )
		     ->addPayerReference( SampleXmlValidationUtils::CARD_ADD_PAYER_REF )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_ADD_CARD_HOLDER_NAME )
		     ->addNumber( SampleXmlValidationUtils::CARD_ADD_NUMBER );


		$request = new PaymentRequest();
		$request->addType( PaymentType::CARD_NEW )
		        ->addTimeStamp( SampleXmlValidationUtils::CARD_ADD_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::CARD_ADD_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::CARD_ADD_ORDER_ID )
		        ->addCard( $card );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::CARD_ADD_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a card-update transaction.
	 */
	public function testCardUpdateHashGeneration() {

		$card = new Card();
		$card->addReference( SampleXmlValidationUtils::CARD_ADD_REF )
		     ->addPayerReference( SampleXmlValidationUtils::CARD_UPDATE_PAYER_REF )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_UPDATE_CARD_HOLDER_NAME )
		     ->addExpiryDate( SampleXmlValidationUtils::CARD_UPDATE_EXP_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_UPDATE_NUMBER );


		$request = new PaymentRequest();
		$request->addType( PaymentType::CARD_UPDATE )
		        ->addTimeStamp( SampleXmlValidationUtils::CARD_UPDATE_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::CARD_UPDATE_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::CARD_UPDATE_ORDER_ID )
		        ->addCard( $card );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::CARD_UPDATE_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a card-update transaction.
	 */
	public function testCardDeleteHashGeneration() {

		$card = new Card();
		$card->addReference( SampleXmlValidationUtils::CARD_DELETE_REF )
		     ->addPayerReference( SampleXmlValidationUtils::CARD_DELETE_PAYER_REF );


		$request = new PaymentRequest();
		$request->addType( PaymentType::CARD_CANCEL )
		        ->addTimeStamp( SampleXmlValidationUtils::CARD_DELETE_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::CARD_DELETE_MERCHANT_ID )
		        ->addCard( $card );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::CARD_DELETE_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a card-update transaction.
	 */
	public function testDccInfoHashGeneration() {

		$card = new Card();
		$card
			->addNumber( SampleXmlValidationUtils::DCC_RATE_CARD_NUMBER )
			->addExpiryDate( SampleXmlValidationUtils::DCC_RATE_CARD_EXPIRY_DATE )
			->addCardHolderName( SampleXmlValidationUtils::DCC_RATE_CARD_HOLDER_NAME )
			->addType( SampleXmlValidationUtils::DCC_RATE_CARD_TYPE );

		// add dccinfo. Note that the type is not set as it is already defaulted to 1
		$dccInfo = ( new DccInfo() );
		$dccInfo->addDccProcessor( SampleXmlValidationUtils::DCC_RATE_CCP );


		$request = new PaymentRequest();
		$request
			->addType( PaymentType::DCC_RATE_LOOKUP )
			->addTimeStamp( SampleXmlValidationUtils::DCC_RATE_TIMESTAMP )
			->addMerchantId( SampleXmlValidationUtils::DCC_RATE_MERCHANT_ID )
			->addAmount( SampleXmlValidationUtils::DCC_RATE_AMOUNT )
			->addCurrency( SampleXmlValidationUtils::DCC_RATE_CURRENCY )
			->addOrderId( SampleXmlValidationUtils::DCC_RATE_ORDER_ID )
			->addCard( $card )
			->addDccInfo( $dccInfo );


		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::DCC_RATE_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the hash calculation for a card-update transaction.
	 */
	public function testDccAuthHashGeneration() {

		$card = new Card();
		$card
			->addNumber( SampleXmlValidationUtils::DCC_AUTH_CARD_NUMBER )
			->addExpiryDate( SampleXmlValidationUtils::DCC_AUTH_CARD_EXPIRY_DATE )
			->addCardHolderName( SampleXmlValidationUtils::DCC_AUTH_CARD_HOLDER_NAME )
			->addType( SampleXmlValidationUtils::DCC_AUTH_CARD_TYPE );

		// add dccinfo. Note that the type is not set as it is already defaulted to 1
		$dccInfo = ( new DccInfo() );
		$dccInfo
			->addDccProcessor( SampleXmlValidationUtils::DCC_AUTH_CCP );


		$request = new PaymentRequest();
		$request
			->addType( PaymentType::DCC_AUTH )
			->addTimeStamp( SampleXmlValidationUtils::DCC_AUTH_TIMESTAMP )
			->addMerchantId( SampleXmlValidationUtils::DCC_AUTH_MERCHANT_ID )
			->addAmount( SampleXmlValidationUtils::DCC_AUTH_AMOUNT )
			->addCurrency( SampleXmlValidationUtils::DCC_AUTH_CURRENCY )
			->addOrderId( SampleXmlValidationUtils::DCC_AUTH_ORDER_ID )
			->addCard( $card )
			->addDccInfo( $dccInfo );


		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::DCC_AUTH_REQUEST_HASH, $request->getHash() );
	}


	/**
	 * Tests the hash calculation for a receipt-in otb payment.
	 */
	public function testReceiptInOTBHashGeneration() {
		$request = new PaymentRequest();
		$request->addType( PaymentType::RECEIPT_IN_OTB )
		        ->addTimeStamp( SampleXmlValidationUtils::RECEIPT_IN_OTB_TIMESTAMP )
		        ->addMerchantId( SampleXmlValidationUtils::RECEIPT_IN_OTB_MERCHANT_ID )
		        ->addOrderId( SampleXmlValidationUtils::RECEIPT_IN_OTB_ORDER_ID )
		        ->addAmount( SampleXmlValidationUtils::RECEIPT_IN_OTB_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::RECEIPT_IN_OTB_CURRENCY )
		        ->addPayerReference( SampleXmlValidationUtils::RECEIPT_IN_OTB_PAYER );

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::RECEIPT_IN_OTB_REQUEST_HASH, $request->getHash() );
	}

	/**
	 * Tests the reasons code
	 */
	public function testAddFraudReason() {
		$request =  new PaymentRequest() ;

		$request ->addAccount( "myAccount" )
		->addMerchantId( "myMerchantId" )
		->addType( PaymentType::RELEASE )
		->addOrderId("Order ID from original transaction")
		->addReasonCode( ReasonCode::FRAUD)
		->addPaymentsReference("Pasref from original transaction");


		$this->assertEquals($request->getReasonCode(), ReasonCode::FRAUD );
	}

	/**
	 * Tests the reasons code
	 */
	public function testAddFraudReasonNotEmpty() {
		$request =  new PaymentRequest() ;

		$request ->addAccount( "myAccount" )
			->addMerchantId( "myMerchantId" )
			->addType( PaymentType::RELEASE )
			->addOrderId("Order ID from original transaction")
			->addReasonCode( ReasonCode::FRAUD)
			->addPaymentsReference("Pasref from original transaction");

		$this->assertNotEmpty($request->getReasonCode() );
	}

	/**
	 * Tests the reasons code
	 */
	public function testAddFraudReasonNotEmpty2() {
		$request =  new PaymentRequest() ;

		$this->assertEmpty($request->getReasonCode() );
		$request->addReasonCode( ReasonCode::FRAUD);
		$this->assertNotEmpty($request->getReasonCode() );

	}

	/**
	 * Tests the reasons code
	 */
	public function testAddFraudReasonChanging() {
		$request =  new PaymentRequest() ;
		$request->addReasonCode( ReasonCode::FALSE_POSITIVE);
		$request->setReasonCode( ReasonCode::FRAUD);

		$this->assertEquals($request->getReasonCode(), ReasonCode::FRAUD );
	}

	/**
	 * Tests the hash calculation for a card-update transaction.
	 */
	public function testDccRealVault() {

		// add dccinfo. Note that the type is not set as it is already defaulted to 1
		$dccInfo = ( new DccInfo() );
		$dccInfo->addDccProcessor( SampleXmlValidationUtils::DCC_REAL_VAULT_DCC_CCP );


		$request = new PaymentRequest();
		$request
			->addType( PaymentType::STORED_CARD_DCC_RATE )
			->addTimeStamp( SampleXmlValidationUtils::DCC_REAL_VAULT_TIMESTAMP )
			->addMerchantId( SampleXmlValidationUtils::DCC_REAL_VAULT_MERCHANT_ID )
			->addAmount( SampleXmlValidationUtils::DCC_REAL_VAULT_AMOUNT )
			->addCurrency( SampleXmlValidationUtils::DCC_REAL_VAULT_CURRENCY )
			->addOrderId( SampleXmlValidationUtils::DCC_REAL_VAULT_ORDER_ID )
			->addDccInfo( $dccInfo );


		$this->assertEquals( SampleXmlValidationUtils::STORED_CARD_DCC_RATE, $request->getType() );
	}

	/**
	 * Tests the hash calculation for a realvault payment.
	 */
	public function testRealVaultHashGeneration() {
		$request = new PaymentRequest();
		$request
			->addType( PaymentType::STORED_CARD_DCC_RATE )
			->addTimeStamp( SampleXmlValidationUtils::DCC_REAL_VAULT_TIMESTAMP )
			->addMerchantId( SampleXmlValidationUtils::DCC_REAL_VAULT_MERCHANT_ID )
			->addAmount( SampleXmlValidationUtils::DCC_REAL_VAULT_AMOUNT )
			->addCurrency( SampleXmlValidationUtils::DCC_REAL_VAULT_CURRENCY )
			->addOrderId( SampleXmlValidationUtils::DCC_REAL_VAULT_ORDER_ID )
			->addPayerReference(SampleXmlValidationUtils::DCC_REAL_VAULT_PAYREF);

		$request->hash( SampleXmlValidationUtils::SECRET );

		$this->assertEquals( SampleXmlValidationUtils::DCC_REAL_VAULT_REQUEST_HASH, $request->getHash() );
	}

}
