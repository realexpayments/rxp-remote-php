<?php


namespace com\realexpayments\remote\sdk\domain\payment;


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
	public function  testAddAddressVerificationServiceDetails1() {
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
	public function  testAddAddressVerificationServiceDetails2() {
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
	public function  testAddAddressVerificationServiceDetails3() {
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
	public function  testAddAddressVerificationServiceDetails4() {
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
	public function  testAddAddressVerificationServiceDetails5() {
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
	public function  testAddAddressVerificationServiceDetailsWithTssInfo() {
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




}
