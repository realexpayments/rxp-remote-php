<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\domain\payment\Address;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;
use com\realexpayments\remote\sdk\domain\payment\Mpi;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentType;
use com\realexpayments\remote\sdk\domain\payment\Recurring;
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
	public function testPaymentRequestXmlHelpers() {
		$cvn = ( new CVN() )
			->addNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER )
			->addPresenceIndicatorType( SampleXmlValidationUtils::$CARD_CVN_PRESENCE );

		$card = ( new Card() )
			->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
			->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
			->addType( new CardType( CardType::VISA ) )
			->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
			->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$card->setCvn( $cvn );

		$tssInfo = ( new TssInfo() )
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( ( new Address() )
				->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS ) )
			->addAddress( ( new Address() )
				->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING ) );

		$request = ( new PaymentRequest() )
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addPaymentType( new PaymentType( PaymentType::AUTH ) )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( ( new AutoSettle() )->addAutoSettleFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG ) )
			->addTimestamp( SampleXmlValidationUtils::TIMESTAMP )
			->addChannel( SampleXmlValidationUtils::CHANNEL )
			->addOrderId( SampleXmlValidationUtils::ORDER_ID )
			->addHash( SampleXmlValidationUtils::REQUEST_HASH )
			->addComment( SampleXmlValidationUtils::COMMENT1 )
			->addComment( SampleXmlValidationUtils::COMMENT2 )
			->addPaymentsReference( SampleXmlValidationUtils::PASREF )
			->addAuthCode( SampleXmlValidationUtils::AUTH_CODE )
			->addRefundHash( SampleXmlValidationUtils::REFUND_HASH )
			->addFraudFilter( SampleXmlValidationUtils::FRAUD_FILTER )
			->addRecurring( new Recurring() )// TODO: Add recurring info
			->addTssInfo( $tssInfo )
			->addMpi( new Mpi() ); // TODO: Add 3DS info

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = ( new PaymentRequest() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	public function  testPaymentRequestXmlHelpersNoEnums() {
		$card = ( new Card() )
			->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
			->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
			->addType( new CardType( CardType::VISA ) )
			->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
			->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
			->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() )
			->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = ( new TssInfo() )
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( ( new Address() )
				->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS->getAddressType() )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS ) )
			->addAddress( ( new Address() )
				->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING->getAddressType() )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING ) );

		$request = ( new PaymentRequest() )
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( ( new PaymentType( PaymentType::AUTH ) )->getType() )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( ( new AutoSettle() )->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() ) )
			->addTimestamp( SampleXmlValidationUtils::TIMESTAMP )
			->addChannel( SampleXmlValidationUtils::CHANNEL )
			->addOrderId( SampleXmlValidationUtils::ORDER_ID )
			->addHash( SampleXmlValidationUtils::REQUEST_HASH )
			->addComment( SampleXmlValidationUtils::COMMENT1 )
			->addComment( SampleXmlValidationUtils::COMMENT2 )
			->addPaymentsReference( SampleXmlValidationUtils::PASREF )
			->addAuthCode( SampleXmlValidationUtils::AUTH_CODE )
			->addRefundHash( SampleXmlValidationUtils::REFUND_HASH )
			->addFraudFilter( SampleXmlValidationUtils::FRAUD_FILTER )
			->addRecurring( new Recurring() )// TODO: Add recurring info
			->addTssInfo( $tssInfo )
			->addMpi( new Mpi() ); // TODO: Add 3DS info


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = ( new PaymentRequest() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );


	}

}
