<?php


namespace com\realexpayments\remote\sdk\utils;


use DateInterval;
use DateTime;

class CardValidationUtilsTest extends \PHPUnit_Framework_TestCase {

	const  VALID_CARD_NUM = "4242424242424242";
	const  INVALID_CARD_NUM = "1234567890123456";
	const  VALID_CARD_NUM_WITH_SPACES = "4242 4242 4242 4242";
	const  INVALID_CARD_NUM_WITH_SPACES = "1234 5678 9012 3456";
	const  ALPHA_STRING = "abcdefghijklmop";
	const  EMPTY_STRING = "";


	public function testValidCardNumber() {
		$cardIsValid = CardValidationUtils::performLuhnCheck( self::VALID_CARD_NUM );
		$this->assertTrue( $cardIsValid, "Test Valid Card Number " . self::VALID_CARD_NUM );
	}


	public function testInvalidCardNumber() {
		$cardIsValid = CardValidationUtils::performLuhnCheck( self::INVALID_CARD_NUM );
		$this->assertFalse( $cardIsValid, "Test Invalid Card Number " . self:: INVALID_CARD_NUM );
	}


	public function testValidCardNumberWithSpaces() {
		$cardIsValid = CardValidationUtils::performLuhnCheck( self::VALID_CARD_NUM_WITH_SPACES );
		$this->assertFalse( $cardIsValid, "Test Valid Card Number " . self::VALID_CARD_NUM_WITH_SPACES );
	}


	public function testInvalidCardNumberWithSpaces() {
		$cardIsValid = CardValidationUtils::performLuhnCheck( self::INVALID_CARD_NUM_WITH_SPACES );
		$this->assertFalse( $cardIsValid, "Test Invalid Card Number " . self::INVALID_CARD_NUM_WITH_SPACES );
	}


	public function testAlphaStringAsCardNumber() {
		$cardIsValid = CardValidationUtils::performLuhnCheck( self::ALPHA_STRING );
		$this->assertFalse( $cardIsValid, "Test Invalid Card Number " . self::ALPHA_STRING );
	}


	public function testEmptyStringAsCardNumber() {
		$cardIsValid = CardValidationUtils::performLuhnCheck( self::EMPTY_STRING );
		$this->assertFalse( $cardIsValid, "Test Invalid Card Number " . self::EMPTY_STRING );
	}


	public function testValidAmexCvv() {
		$cvvNumber = "1234";
		$cardType  = "AMEX";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertTrue( $cvvIsValid, "Testing valid " . $cardType . " card type with CVV number " . $cvvNumber . self::EMPTY_STRING );
	}


	public function testValidAmexLowerCaseCvv() {
		$cvvNumber = "1234";
		$cardType  = "amex";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertTrue( $cvvIsValid, "Testing valid " . $cardType . " card type with CVV number " . $cvvNumber . self::EMPTY_STRING );
	}


	public function testInvalidAmexCvv() {
		$cvvNumber = "12345";
		$cardType  = "AMEX";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertFalse( $cvvIsValid, "Testing invalid " . $cardType . " card type with CVV number " . $cvvNumber . self::EMPTY_STRING );
	}


	public function testValidVisaCvv() {
		$cvvNumber = "123";
		$cardType  = "VISA";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertTrue( $cvvIsValid, "Testing valid " . $cardType . " card type with CVV number " . $cvvNumber . self::EMPTY_STRING );
	}


	public function testInvalidVisaCvv() {
		$cvvNumber = "1234";
		$cardType  = "VISA";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertFalse( $cvvIsValid, "Testing valid " . $cardType . " card type with CVV number " . $cvvNumber . self::EMPTY_STRING );
	}


	public function testValidExpiryDateCurrentMonthThisYear() {

		$message = "Correct date MMYY - this month";

		$mm = date( "m" );
		$yy = date( "y" );

		$expiryDate     = $mm . $yy;
		$expectedResult = true;

		$result = CardValidationUtils::performExpiryDateCheck( $expiryDate );
		$this->assertEquals( $expectedResult, $result, $message . " : " . $expiryDate );
	}


	public function testValidExpiryDateFutureMonthThisYear() {

		$message = "Correct date MMYY - this month";

		$date = new DateTime();
		$date->add( new DateInterval( 'P1M' ) ); // Move to next month (if December will be next year but will have to live with that

		$mm = $date->format( "m" );
		$yy = $date->format( "y" );


		$expiryDate     = $mm . $yy;
		$expectedResult = true;

		$result = CardValidationUtils::performExpiryDateCheck( $expiryDate );
		$this->assertEquals( $expectedResult, $result, $message . " : " . $expiryDate );
	}


	public function testValidExpiryDatePastMonthThisYear() {

		$message = "Correct date MMYY - this month";

		$date = new DateTime();
		$date->sub( new DateInterval( 'P1M' ) ); // Move to last month (if January will be last year but will have to live with that

		$mm = $date->format( "m" );
		$yy = $date->format( "y" );


		$expiryDate     = $mm . $yy;
		$expectedResult = false;

		$result = CardValidationUtils::performExpiryDateCheck( $expiryDate );
		$this->assertEquals( $expectedResult, $result, $message . " : " . $expiryDate );
	}

	public function testLetterOnCVV() {
		$cvvNumber = "aaa";
		$cardType  = "VISA";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertFalse( $cvvIsValid, "Testing valid " . $cardType . " card type with invalid CVV number " . $cvvNumber . self::EMPTY_STRING );
	}

	public function testOneLetterOnCVV() {
		$cvvNumber = "a";
		$cardType  = "VISA";

		$cvvIsValid = CardValidationUtils::performCvvCheck( $cvvNumber, $cardType );
		$this->assertFalse( $cvvIsValid, "Testing valid " . $cardType . " card type with invalid CVV number " . $cvvNumber . self::EMPTY_STRING );
	}


}
