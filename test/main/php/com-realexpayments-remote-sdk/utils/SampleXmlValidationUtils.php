<?php


namespace com\realexpayments\remote\sdk\utils;

use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\domain\payment\AddressType;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;
use com\realexpayments\remote\sdk\domain\payment\AutoSettleFlag;
use com\realexpayments\remote\sdk\domain\PresenceIndicator;
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
	//const CardType CARD_TYPE = CardType . VISA;
	const CARD_HOLDER_NAME = "Joe Smith";
	const  CARD_CVN_NUMBER = 123;
	public static $CARD_CVN_PRESENCE;
	const CARD_EXPIRY_DATE = "0119";
	const CARD_ISSUE_NUMBER = 1;

	//PaymentRequest
	const ACCOUNT = "internet";
	const MERCHANT_ID = "thestore";
	const AMOUNT = 29900;
	const CURRENCY = "EUR";
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
//public static final RecurringType RECURRING_TYPE = RecurringType.FIXED;
//public static final RecurringFlag RECURRING_FLAG = RecurringFlag.ONE;
//public static final RecurringSequence RECURRING_SEQUENCE = RecurringSequence.FIRST;

	//Address
	public static $ADDRESS_TYPE_BUSINESS;
	const ADDRESS_CODE_BUSINESS = "21|578";
	const ADDRESS_COUNTRY_BUSINESS = "IE";

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
		self::$CARD_CVN_PRESENCE = new PresenceIndicator( PresenceIndicator::CVN_PRESENT );
		self::$ADDRESS_TYPE_BUSINESS = new AddressType(AddressType::BILLING);
		self::$ADDRESS_TYPE_SHIPPING = new AddressType(AddressType::SHIPPING);
		self::$AUTO_SETTLE_FLAG= new AutoSettleFlag(AutoSettleFlag::MULTI);
	}

	/**
	 *  Check all fields match expected values.
	 *
	 * @param iResponse $fromXmlResponse
	 * @param PHPUnit_Framework_TestCase $testCase
	 */
	public static function checkUnmarshalledPaymentResponse( iResponse $fromXmlResponse, PHPUnit_Framework_TestCase $testCase ) {
		$testCase->assertEquals( self::ACCOUNT, $fromXmlResponse->getAccount() );
	}

	public static function checkUnmarshalledPaymentRequest( $fromXmlRequest, $this ) {
	}
}

SampleXmlValidationUtils::Init();

