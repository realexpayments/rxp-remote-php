<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\Card;

class CardTest extends \PHPUnit_Framework_TestCase {

	public function testDisplayFirstSixLastFour()
	{
		$card = new Card();
		$card->setNumber("1234567890ABCDEF");

		$expectedResult = "123456******CDEF";
		$result= $card->displayFirstSixLastFour();

		$this->assertTrue($expectedResult === $result,"Results are not as expected");

	}
}
