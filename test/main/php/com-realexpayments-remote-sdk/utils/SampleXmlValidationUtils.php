<?php


namespace com\realexpayments\remote\sdk\utils;

use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\payment\AddressType;
use com\realexpayments\remote\sdk\domain\payment\AutoSettleFlag;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentType;
use com\realexpayments\remote\sdk\domain\payment\RecurringFlag;
use com\realexpayments\remote\sdk\domain\payment\RecurringSequence;
use com\realexpayments\remote\sdk\domain\payment\RecurringType;
use com\realexpayments\remote\sdk\domain\PresenceIndicator;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureRequest;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureResponse;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureType;
use com\realexpayments\remote\sdk\RealexServerException;
use PHPUnit_Framework_TestCase;

class SampleXmlValidationUtils {

	const SECRET = "mysecret";

	//payment sample XML
	const PAYMENT_REQUEST_XML_PATH = "/sample-xml/payment-request-sample.xml";
	const PAYMENT_RESPONSE_XML_PATH = "/sample-xml/payment-response-sample.xml";
	const PAYMENT_RESPONSE_BASIC_ERROR_XML_PATH = "/sample-xml/payment-response-basic-error-sample.xml";
	const PAYMENT_RESPONSE_FULL_ERROR_XML_PATH = "/sample-xml/payment-response-full-error-sample.xml";
	const PAYMENT_RESPONSE_XML_PATH_UNKNOWN_ELEMENT = "/sample-xml/payment-response-sample-unknown-element.xml";

	//3DSecure sample XML
	const THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_XML_PATH = "/sample-xml/3ds-verify-enrolled-request-sample.xml";
	const THREE_D_SECURE_VERIFY_ENROLLED_RESPONSE_XML_PATH = "/sample-xml/3ds-verify-enrolled-response-sample.xml";
	const THREE_D_SECURE_VERIFY_ENROLLED_NOT_ENROLLED_RESPONSE_XML_PATH = "/sample-xml/3ds-verify-enrolled-response-sample-not-enrolled.xml";
	const THREE_D_SECURE_VERIFY_SIG_REQUEST_XML_PATH = "/sample-xml/3ds-verify-sig-request-sample.xml";
	const THREE_D_SECURE_VERIFY_SIG_RESPONSE_XML_PATH = "/sample-xml/3ds-verify-sig-response-sample.xml";

	//Card
	const CARD_NUMBER = "420000000000000000";
	/**
	 * @var CardType
	 */
	static $CARD_TYPE;

	const CARD_HOLDER_NAME = "Joe Smith";
	const  CARD_CVN_NUMBER = 123;
	/**
	 * @var PresenceIndicator
	 */
	public static $CARD_CVN_PRESENCE;
	const CARD_EXPIRY_DATE = "0119";
	const CARD_ISSUE_NUMBER = 1;

	//PaymentRequest
	const ACCOUNT = "internet";
	const MERCHANT_ID = "thestore";
	const AMOUNT = 29900;
	const CURRENCY = "EUR";
	/**
	 * @var AutoSettleFlag
	 */
	static $AUTO_SETTLE_FLAG;
	const TIMESTAMP = "20120926112654";
	const CHANNEL = "yourChannel";
	const ORDER_ID = "ORD453-11";
	const REQUEST_HASH = "581b84c1dbfd0a6c9c7d4e2d0a620157e879dac5";
	const COMMENT1 = "comment 1";
	const COMMENT2 = "comment 2";
	const REFUND_HASH = "hjfdg78h34tyvklasjr89t";
	const FRAUD_FILTER = "fraud filter";
	const CUSTOMER_NUMBER = "cust num";
	const PRODUCT_ID = "prod ID";
	const VARIABLE_REFERENCE = "variable ref 1234";
	const CUSTOMER_IP = "127.0.0.1";

	//Recurring
	/**
	 * @var RecurringType
	 */
	public static $RECURRING_TYPE;

	/**
	 * @var RecurringFlag
	 */
	public static $RECURRING_FLAG;

	/**
	 * @var RecurringSequence
	 */
	public static $RECURRING_SEQUENCE;

	//Address
	/**
	 * @var AddressType
	 */
	public static $ADDRESS_TYPE_BUSINESS;
	const ADDRESS_CODE_BUSINESS = "21|578";
	const ADDRESS_COUNTRY_BUSINESS = "IE";

	/**
	 * @var AddressType
	 */
	public static $ADDRESS_TYPE_SHIPPING;
	const ADDRESS_CODE_SHIPPING = "77|9876";
	const ADDRESS_COUNTRY_SHIPPING = "GB";

	//response fields
	const ACQUIRER_RESPONSE = "<response>test acquirer response</response>";
	const AUTH_TIME_TAKEN = 1001;
	const BATCH_ID = - 1;
	const BANK = "bank";
	const COUNTRY = "Ireland";
	const COUNTRY_CODE = "IE";
	const REGION = "Dublin";
	const CVN_RESULT = "M";
	const MESSAGE = "Successful";
	const RESULT_SUCCESS = "00";
	const TIME_TAKEN = 54564;
	const TSS_RESULT = "67";
	const TSS_RESULT_CHECK1_ID = "1";
	const TSS_RESULT_CHECK2_ID = "2";
	const TSS_RESULT_CHECK1_VALUE = "99";
	const TSS_RESULT_CHECK2_VALUE = "199";
	const RESPONSE_HASH = "368df010076481d47a21e777871012b62b976339";
	const PASREF = "3737468273643";
	const AUTH_CODE = "79347";
	const AVS_POSTCODE = "M";
	const AVS_ADDRESS = "P";

	//basic response error fields
	const MESSAGE_BASIC_ERROR = "error message returned from system";
	const RESULT_BASIC_ERROR = "508";
	const TIMESTAMP_BASIC_ERROR = "20120926112654";
	const ORDER_ID_BASIC_ERROR = "ORD453-11";

	//basic response error fields
	const RESULT_FULL_ERROR = "101";
	const MESSAGE_FULL_ERROR = "Bank Error";
	const RESPONSE_FULL_ERROR_HASH = "0ad8a9f121c4041b4b832ae8069e80674269e22f";

	//3DS request fields
	const THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_HASH = "1f6db5dc1a72c35b4c07cc9405a9674e272d57e7";
	const THREE_D_SECURE_VERIFY_SIG_REQUEST_HASH = "1f6db5dc1a72c35b4c07cc9405a9674e272d57e7";

	//3DS response fields
	const THREE_D_SECURE_ENROLLED_RESULT = "00";
	const THREE_D_SECURE_SIG_RESULT = "00";
	const THREE_D_SECURE_NOT_ENROLLED_RESULT = "110";
	const THREE_D_SECURE_ENROLLED_MESSAGE = "Enrolled";
	const THREE_D_SECURE_SIG_MESSAGE = "Authentication Successful";
	const THREE_D_SECURE_NOT_ENROLLED_MESSAGE = "Not Enrolled";
	const THREE_D_SECURE_PAREQ = "eJxVUttygkAM/ZUdnitZFlBw4na02tE6bR0vD+0bLlHpFFDASv++u6i1";
	const THREE_D_SECURE_PARES = "eJxVUttygkAM/ZUdnitZFlBw4na02tE6bR0vD+0bLlHpFFDASv++u6i1";
	const THREE_D_SECURE_URL = "http://myurl.com";
	const THREE_D_SECURE_ENROLLED_NO = "N";
	const THREE_D_SECURE_ENROLLED_YES = "Y";
	const THREE_D_SECURE_STATUS = "Y";
	const THREE_D_SECURE_ECI = "5";
	const THREE_D_SECURE_XID = "e9dafe706f7142469c45d4877aaf5984";
	const THREE_D_SECURE_CAVV = "AAABASY3QHgwUVdEBTdAAAAAAAA=";
	const THREE_D_SECURE_ALGORITHM = "1";
	const THREE_D_SECURE_NOT_ENROLLED_RESPONSE_HASH = "e553ff2510dec9bfee79bb0303af337d871c02ad";
	const THREE_D_SECURE_ENROLLED_RESPONSE_HASH = "728cdbef90ff535ed818748f329ed8b1df6b8f5a";
	const THREE_D_SECURE_SIG_RESPONSE_HASH = "e5a7745da5dc32d234c3f52860132c482107e9ac";

	static function Init() {
		self::$CARD_CVN_PRESENCE     = new PresenceIndicator( PresenceIndicator::CVN_PRESENT );
		self::$ADDRESS_TYPE_BUSINESS = new AddressType( AddressType::BILLING );
		self::$ADDRESS_TYPE_SHIPPING = new AddressType( AddressType::SHIPPING );
		self::$AUTO_SETTLE_FLAG      = new AutoSettleFlag( AutoSettleFlag::MULTI );
		self::$CARD_TYPE             = new CardType( CardType::VISA );
		self::$RECURRING_TYPE        = new RecurringType( RecurringType::FIXED );
		self::$RECURRING_FLAG        = new RecurringFlag( RecurringFlag::ONE );
		self::$RECURRING_SEQUENCE    = new RecurringSequence( RecurringSequence::FIRST );
	}

	/**
	 *  Check all fields match expected values.
	 *
	 * @param PaymentResponse $fromXmlResponse
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkUnmarshalledPaymentResponse( PaymentResponse $fromXmlResponse, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertEquals( self::ACCOUNT, $fromXmlResponse->getAccount() );
		$testCase->assertEquals( self::ACQUIRER_RESPONSE, $fromXmlResponse->getAcquirerResponse() );
		$testCase->assertEquals( self::AUTH_CODE, $fromXmlResponse->getAuthCode() );
		$testCase->assertEquals( self::AUTH_TIME_TAKEN, $fromXmlResponse->getAuthTimeTaken() );
		$testCase->assertEquals( self::BATCH_ID, $fromXmlResponse->getBatchId() );
		$testCase->assertEquals( self::BANK, $fromXmlResponse->getCardIssuer()->getBank() );
		$testCase->assertEquals( self::COUNTRY, $fromXmlResponse->getCardIssuer()->getCountry() );
		$testCase->assertEquals( self::COUNTRY_CODE, $fromXmlResponse->getCardIssuer()->getCountryCode() );
		$testCase->assertEquals( self::REGION, $fromXmlResponse->getCardIssuer()->getRegion() );
		$testCase->assertEquals( self::CVN_RESULT, $fromXmlResponse->getCvnResult() );
		$testCase->assertEquals( self::MERCHANT_ID, $fromXmlResponse->getMerchantId() );
		$testCase->assertEquals( self::MESSAGE, $fromXmlResponse->getMessage() );
		$testCase->assertEquals( self::ORDER_ID, $fromXmlResponse->getOrderId() );
		$testCase->assertEquals( self::PASREF, $fromXmlResponse->getPaymentsReference() );
		$testCase->assertEquals( self::RESULT_SUCCESS, $fromXmlResponse->getResult() );
		$testCase->assertEquals( self::RESPONSE_HASH, $fromXmlResponse->getHash() );
		$testCase->assertEquals( self::TIMESTAMP, $fromXmlResponse->getTimeStamp() );
		$testCase->assertEquals( self::TIME_TAKEN, $fromXmlResponse->getTimeTaken() );
		$testCase->assertEquals( self::TSS_RESULT, $fromXmlResponse->getTssResult()->getResult() );
		$checks = $fromXmlResponse->getTssResult()->getChecks();
		$testCase->assertEquals( self::TSS_RESULT_CHECK1_ID, $checks[0]->getId() );
		$testCase->assertEquals( self::TSS_RESULT_CHECK1_VALUE, $checks[0]->getValue() );
		$testCase->assertEquals( self::TSS_RESULT_CHECK2_ID, $checks[1]->getId() );
		$testCase->assertEquals( self::TSS_RESULT_CHECK2_VALUE, $checks[1]->getValue() );
		$testCase->assertEquals( self::AVS_ADDRESS, $fromXmlResponse->getAvsAddressResponse() );
		$testCase->assertEquals( self::AVS_POSTCODE, $fromXmlResponse->getAvsPostcodeResponse() );
		$testCase->assertTrue( $fromXmlResponse->isSuccess() );
	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param PaymentRequest $fromXmlRequest
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkUnmarshalledPaymentRequest( PaymentRequest $fromXmlRequest, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertNotNull( $fromXmlRequest );
		$testCase->assertEquals( self::CARD_NUMBER, $fromXmlRequest->getCard()->getNumber() );

		$testCase->assertEquals( self::$CARD_TYPE->getType(), $fromXmlRequest->getCard()->getType() );
		$testCase->assertEquals( self::CARD_HOLDER_NAME, $fromXmlRequest->getCard()->getCardHolderName() );
		$testCase->assertEquals( self::CARD_CVN_NUMBER, $fromXmlRequest->getCard()->getCvn()->getNumber() );
		$testCase->assertEquals( self::$CARD_CVN_PRESENCE->getIndicator(), $fromXmlRequest->getCard()->getCvn()->getPresenceIndicator() );
		$testCase->assertEquals( self::CARD_ISSUE_NUMBER, $fromXmlRequest->getCard()->getIssueNumber() );
		$testCase->assertEquals( self::CARD_EXPIRY_DATE, $fromXmlRequest->getCard()->getExpiryDate() );

		$testCase->assertEquals( self::ACCOUNT, $fromXmlRequest->getAccount() );
		$testCase->assertEquals( self::MERCHANT_ID, $fromXmlRequest->getMerchantId() );
		$testCase->assertEquals( PaymentType::AUTH, $fromXmlRequest->getType() );
		$testCase->assertEquals( self:: AMOUNT, $fromXmlRequest->getAmount()->getAmount() );
		$testCase->assertEquals( self::CURRENCY, $fromXmlRequest->getAmount()->getCurrency() );
		$testCase->assertEquals( self::$AUTO_SETTLE_FLAG->getFlag(), $fromXmlRequest->getAutoSettle()->getFlag() );
		$testCase->assertEquals( self::TIMESTAMP, $fromXmlRequest->getTimeStamp() );
		$testCase->assertEquals( self::CHANNEL, $fromXmlRequest->getChannel() );
		$testCase->assertEquals( self::ORDER_ID, $fromXmlRequest->getOrderId() );
		$testCase->assertEquals( self::REQUEST_HASH, $fromXmlRequest->getHash() );
		$testCase->assertEquals( self::COMMENT1, $fromXmlRequest->getComments()->get( 0 )->getComment() );
		$testCase->assertEquals( "1", $fromXmlRequest->getComments()->get( 0 )->getId() );
		$testCase->assertEquals( self::COMMENT2, $fromXmlRequest->getComments()->get( 1 )->getComment() );
		$testCase->assertEquals( "2", $fromXmlRequest->getComments()->get( 1 )->getId() );
		$testCase->assertEquals( self::PASREF, $fromXmlRequest->getPaymentsReference() );
		$testCase->assertEquals( self::AUTH_CODE, $fromXmlRequest->getAuthCode() );
		$testCase->assertEquals( self::REFUND_HASH, $fromXmlRequest->getRefundHash() );
		$testCase->assertEquals( self::FRAUD_FILTER, $fromXmlRequest->getFraudFilter() );

		$testCase->assertEquals( self::$RECURRING_FLAG->getRecurringFlag(), $fromXmlRequest->getRecurring()->getFlag() );
		$testCase->assertEquals( self::$RECURRING_TYPE->getType(), $fromXmlRequest->getRecurring()->getType() );
		$testCase->assertEquals( self::$RECURRING_SEQUENCE->getSequence(), $fromXmlRequest->getRecurring()->getSequence() );

		$testCase->assertEquals( self::CUSTOMER_NUMBER, $fromXmlRequest->getTssInfo()->getCustomerNumber() );
		$testCase->assertEquals( self::PRODUCT_ID, $fromXmlRequest->getTssInfo()->getProductId() );
		$testCase->assertEquals( self::VARIABLE_REFERENCE, $fromXmlRequest->getTssInfo()->getVariableReference() );
		$testCase->assertEquals( self::CUSTOMER_IP, $fromXmlRequest->getTssInfo()->getCustomerIpAddress() );
		$addresses = $fromXmlRequest->getTssInfo()->getAddresses();
		$testCase->assertEquals( self::$ADDRESS_TYPE_BUSINESS->getAddressType(), $addresses[0]->getType() );
		$testCase->assertEquals( self::ADDRESS_CODE_BUSINESS, $addresses[0]->getCode() );
		$testCase->assertEquals( self::ADDRESS_COUNTRY_BUSINESS, $addresses[0]->getCountry() );
		$testCase->assertEquals( self::$ADDRESS_TYPE_SHIPPING->getAddressType(), $addresses[1]->getType() );
		$testCase->assertEquals( self::ADDRESS_CODE_SHIPPING, $addresses[1]->getCode() );
		$testCase->assertEquals( self::ADDRESS_COUNTRY_SHIPPING, $addresses[1]->getCountry() );

		$testCase->assertEquals( self::THREE_D_SECURE_CAVV, $fromXmlRequest->getMpi()->getCavv() );
		$testCase->assertEquals( self::THREE_D_SECURE_XID, $fromXmlRequest->getMpi()->getXid() );
		$testCase->assertEquals( self::THREE_D_SECURE_ECI, $fromXmlRequest->getMpi()->getEci() );


	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param RealexServerException $ex
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkBasicResponseError( RealexServerException $ex, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertEquals( self::RESULT_BASIC_ERROR, $ex->getErrorCode() );
		$testCase->assertEquals( self::MESSAGE_BASIC_ERROR, $ex->getMessage() );
		$testCase->assertEquals( self::TIMESTAMP_BASIC_ERROR, $ex->getTimeStamp() );
		$testCase->assertEquals( self::ORDER_ID_BASIC_ERROR, $ex->getOrderId() );
	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param PaymentResponse $response
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkFullResponseError( PaymentResponse $response, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertEquals( self::ACCOUNT, $response->getAccount() );
		$testCase->assertEquals( self::ACQUIRER_RESPONSE, $response->getAcquirerResponse() );
		$testCase->assertEquals( self::AUTH_CODE, $response->getAuthCode() );
		$testCase->assertEquals( self::AUTH_TIME_TAKEN, $response->getAuthTimeTaken() );
		$testCase->assertEquals( self::BATCH_ID, $response->getBatchId() );
		$testCase->assertEquals( self::BANK, $response->getCardIssuer()->getBank() );
		$testCase->assertEquals( self::COUNTRY, $response->getCardIssuer()->getCountry() );
		$testCase->assertEquals( self::COUNTRY_CODE, $response->getCardIssuer()->getCountryCode() );
		$testCase->assertEquals( self::REGION, $response->getCardIssuer()->getRegion() );
		$testCase->assertEquals( self::CVN_RESULT, $response->getCvnResult() );
		$testCase->assertEquals( self::MERCHANT_ID, $response->getMerchantId() );
		$testCase->assertEquals( self::MESSAGE_FULL_ERROR, $response->getMessage() );
		$testCase->assertEquals( self::ORDER_ID, $response->getOrderId() );
		$testCase->assertEquals( self::PASREF, $response->getPaymentsReference() );
		$testCase->assertEquals( self::RESULT_FULL_ERROR, $response->getResult() );
		$testCase->assertEquals( self::RESPONSE_FULL_ERROR_HASH, $response->getHash() );
		$testCase->assertEquals( self::TIMESTAMP, $response->getTimeStamp() );
		$testCase->assertEquals( self::TIME_TAKEN, $response->getTimeTaken() );
		$testCase->assertEquals( self::TSS_RESULT, $response->getTssResult()->getResult() );
		$checks = $response->getTssResult()->getChecks();
		$testCase->assertEquals( self::TSS_RESULT_CHECK1_ID, $checks[0]->getId() );
		$testCase->assertEquals( self::TSS_RESULT_CHECK1_VALUE, $checks[0]->getValue() );
		$testCase->assertEquals( self::TSS_RESULT_CHECK2_ID, $checks[1]->getId() );
		$testCase->assertEquals( self::TSS_RESULT_CHECK2_VALUE, $checks[1]->getValue() );
		$testCase->assertFalse( $response->isSuccess() );
	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param ThreeDSecureRequest $fromXmlRequest
	 * @param PHPUnit_Framework_TestCase $testCase
	 *
	 */
	public static function checkUnmarshalledVerifyEnrolledRequest( ThreeDSecureRequest $fromXmlRequest, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertNotNull( $fromXmlRequest );
		$testCase->assertEquals( self::CARD_NUMBER, $fromXmlRequest->getCard()->getNumber() );
		$testCase->assertEquals( self::$CARD_TYPE->getType(), $fromXmlRequest->getCard()->getType() );
		$testCase->assertEquals( self::CARD_HOLDER_NAME, $fromXmlRequest->getCard()->getCardHolderName() );
		$testCase->assertEquals( self::CARD_EXPIRY_DATE, $fromXmlRequest->getCard()->getExpiryDate() );

		$testCase->assertEquals( self::ACCOUNT, $fromXmlRequest->getAccount() );
		$testCase->assertEquals( self::MERCHANT_ID, $fromXmlRequest->getMerchantId() );
		$testCase->assertEquals( ThreeDSecureType::VERIFY_ENROLLED, $fromXmlRequest->getType() );
		$testCase->assertEquals( self::AMOUNT, $fromXmlRequest->getAmount()->getAmount() );
		$testCase->assertEquals( self::CURRENCY, $fromXmlRequest->getAmount()->getCurrency() );
		$testCase->assertEquals( self::TIMESTAMP, $fromXmlRequest->getTimeStamp() );
		$testCase->assertEquals( self::ORDER_ID, $fromXmlRequest->getOrderId() );

		$testCase->assertEquals( self::THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_HASH, $fromXmlRequest->getHash() );
	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param ThreeDSecureRequest $fromXmlRequest
	 * @param PHPUnit_Framework_TestCase $testCase
	 *
	 */
	public static function checkUnmarshalledVerifySigRequest( ThreeDSecureRequest $fromXmlRequest, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertNotNull( $fromXmlRequest );
		$testCase->assertEquals( self::CARD_NUMBER, $fromXmlRequest->getCard()->getNumber() );
		$testCase->assertEquals( self::$CARD_TYPE->getType(), $fromXmlRequest->getCard()->getType() );
		$testCase->assertEquals( self::CARD_HOLDER_NAME, $fromXmlRequest->getCard()->getCardHolderName() );
		$testCase->assertEquals( self::CARD_EXPIRY_DATE, $fromXmlRequest->getCard()->getExpiryDate() );

		$testCase->assertEquals( self::ACCOUNT, $fromXmlRequest->getAccount() );
		$testCase->assertEquals( self::MERCHANT_ID, $fromXmlRequest->getMerchantId() );
		$testCase->assertEquals( ThreeDSecureType::VERIFY_SIG, $fromXmlRequest->getType() );
		$testCase->assertEquals( self::AMOUNT, $fromXmlRequest->getAmount()->getAmount() );
		$testCase->assertEquals( self::CURRENCY, $fromXmlRequest->getAmount()->getCurrency() );
		$testCase->assertEquals( self::TIMESTAMP, $fromXmlRequest->getTimeStamp() );
		$testCase->assertEquals( self::ORDER_ID, $fromXmlRequest->getOrderId() );

		$testCase->assertEquals( self::THREE_D_SECURE_PARES, $fromXmlRequest->getPares() );
		$testCase->assertEquals( self::THREE_D_SECURE_VERIFY_SIG_REQUEST_HASH, $fromXmlRequest->getHash() );
	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param ThreeDSecureResponse $fromXmlResponse
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkUnmarshalledThreeDSecureEnrolledResponse( ThreeDSecureResponse $fromXmlResponse, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertEquals( self::ACCOUNT, $fromXmlResponse->getAccount() );
		$testCase->assertEquals( self::AUTH_CODE, $fromXmlResponse->getAuthCode() );
		$testCase->assertEquals( self::AUTH_TIME_TAKEN, $fromXmlResponse->getAuthTimeTaken() );
		$testCase->assertEquals( self::MERCHANT_ID, $fromXmlResponse->getMerchantId() );
		$testCase->assertEquals( self::THREE_D_SECURE_ENROLLED_MESSAGE, $fromXmlResponse->getMessage() );
		$testCase->assertEquals( self::ORDER_ID, $fromXmlResponse->getOrderId() );
		$testCase->assertEquals( self::PASREF, $fromXmlResponse->getPaymentsReference() );
		$testCase->assertEquals( self::THREE_D_SECURE_ENROLLED_RESULT, $fromXmlResponse->getResult() );
		$testCase->assertEquals( self::THREE_D_SECURE_ENROLLED_RESPONSE_HASH, $fromXmlResponse->getHash() );
		$testCase->assertEquals( self::TIMESTAMP, $fromXmlResponse->getTimeStamp() );
		$testCase->assertEquals( self::TIME_TAKEN, $fromXmlResponse->getTimeTaken() );
		$testCase->assertEquals( self::THREE_D_SECURE_URL, $fromXmlResponse->getUrl() );
		$testCase->assertEquals( self::THREE_D_SECURE_PAREQ, $fromXmlResponse->getPareq() );
		$testCase->assertEquals( self::THREE_D_SECURE_ENROLLED_YES, $fromXmlResponse->getEnrolled() );
		$testCase->assertEquals( self::THREE_D_SECURE_XID, $fromXmlResponse->getXid() );
		$testCase->assertTrue( $fromXmlResponse->isSuccess() );
	}

	/**
	 * Check all fields match expected values.
	 *
	 * @param ThreeDSecureResponse $fromXmlResponse
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkUnmarshalledThreeDSecureSigResponse( ThreeDSecureResponse $fromXmlResponse, PHPUnit_Framework_TestCase $testCase ) {

		$testCase->assertEquals( self::ACCOUNT, $fromXmlResponse->getAccount() );
		$testCase->assertEquals( self::MERCHANT_ID, $fromXmlResponse->getMerchantId() );
		$testCase->assertEquals( self::THREE_D_SECURE_SIG_MESSAGE, $fromXmlResponse->getMessage() );
		$testCase->assertEquals( self::ORDER_ID, $fromXmlResponse->getOrderId() );
		$testCase->assertEquals( self::THREE_D_SECURE_SIG_RESULT, $fromXmlResponse->getResult() );
		$testCase->assertEquals( self::THREE_D_SECURE_SIG_RESPONSE_HASH, $fromXmlResponse->getHash() );
		$testCase->assertEquals( self::TIMESTAMP, $fromXmlResponse->getTimeStamp() );
		$testCase->assertEquals( self::THREE_D_SECURE_STATUS, $fromXmlResponse->getThreeDSecure()->getStatus() );
		$testCase->assertEquals( self::THREE_D_SECURE_ECI, $fromXmlResponse->getThreeDSecure()->getEci() );
		$testCase->assertEquals( self::THREE_D_SECURE_XID, $fromXmlResponse->getThreeDSecure()->getXid() );
		$testCase->assertEquals( self::THREE_D_SECURE_CAVV, $fromXmlResponse->getThreeDSecure()->getCavv() );
		$testCase->assertEquals( self::THREE_D_SECURE_ALGORITHM, $fromXmlResponse->getThreeDSecure()->getAlgorithm() );
		$testCase->assertTrue( $fromXmlResponse->isSuccess() );
	}


}

SampleXmlValidationUtils::Init();

