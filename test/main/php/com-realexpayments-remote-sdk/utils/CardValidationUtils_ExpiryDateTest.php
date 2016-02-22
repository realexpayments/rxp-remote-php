<?php


namespace com\realexpayments\remote\sdk\utils;


/**
 * Class CardValidationUtils_ExpiryDateTest
 *
 * @package com\realexpayments\remote\sdk\utils
 * @author vicpada
 *
 */
class CardValidationUtils_ExpiryDateTest extends \PHPUnit_Framework_TestCase {


	public function CardValidationUtils_ExpiryDateTestDataProvider() {
		// test with this values
		$testCases = array(
			array( "Correct format MMYY", "1220", true ),
			array( "Correct format MM-YY", "12-20", false ),
			array( "Correct format MM/YY", "12/20", false ),
			array( "Correct format MM YY", "12 20", false ),
			array( "Incorrect format MM\\YY", "12\\20", false ),
			array( "Incorrect format AABB", "AABB", false ),
			array( "Correct future date MMYY", "1221", true ),
			array( "Incorrect date MMYY", "1221", true ),
			array( "Incorrect date MMYY", "1321", false ),
			array( "Incorrect date MMYY", "1212", false ),
			array( "Incorrect date MMYY", "0015", false ),
			array( "Incorrect date MMYY", "0415", false ),
			array( "Correct date MMYY", "1216", true ),
			array( "Incorrect date MMYY", "0021", false )
		);

		return $testCases;
	}


	/**
	 * Test expiry date formats
	 *
	 * @dataProvider CardValidationUtils_ExpiryDateTestDataProvider
	 *
	 * @param string $message
	 * @param string $expiryDate
	 * @param bool $expectedResult
	 */
	public function testExpiryDateFormats( $message, $expiryDate, $expectedResult ) {
		$result = CardValidationUtils::performExpiryDateCheck( $expiryDate );
		$this->assertEquals( $expectedResult, $result, $message . ":" . $expiryDate );

	}

}
