<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\domain\payment\TssInfo;

/**
 * Unit test class for XmlUtils.
 *
 * @author vicpada
 */
class XmlUtilsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using the helper methods.
	 */
	public function paymentRequestXmlHelpersTest()
	{
		$cvn = (new CVN())
			->addNumber(SampleXmlValidationUtils::CARD_CVN_NUMBER)
			->addPresenceIndicator(SampleXmlValidationUtils::$CARD_CVN_PRESENCE);

		$card = (new Card())
			->addExpiryDate(SampleXmlValidationUtils::CARD_EXPIRY_DATE)
			->addNumber(SampleXmlValidationUtils::CARD_NUMBER)
			->addType(new CardType(CardType::VISA))
			->addCardHolderName(SampleXmlValidationUtils::CARD_HOLDER_NAME)
			->addIssueNumber(SampleXmlValidationUtils::CARD_ISSUE_NUMBER);

		$card->setCvn($cvn);

		$tssInfo = (new TssInfo())


	}

}
