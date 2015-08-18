<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\domain\payment\Address;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;
use com\realexpayments\remote\sdk\domain\payment\CardIssuer;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;
use com\realexpayments\remote\sdk\domain\payment\Mpi;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentType;
use com\realexpayments\remote\sdk\domain\payment\Recurring;
use com\realexpayments\remote\sdk\domain\payment\TssInfo;
use com\realexpayments\remote\sdk\domain\payment\TssResult;
use com\realexpayments\remote\sdk\domain\payment\TssResultCheck;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureRequest;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureResponse;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureType;


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
		$cvn = new CVN();
		$cvn = $cvn
			->addNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER )
			->addPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE );


		$card = new Card();
		$card
			->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
			->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
			->addCardType( new CardType( CardType::VISA ) )
			->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
			->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$card->setCvn( $cvn );

		$tssInfo = new TssInfo();

		$businessAddress = new Address();
		$businessAddress ->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                                   ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                                   ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress ->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
		                                   ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
		                                   ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING );
		$tssInfo
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( $businessAddress )
			->addAddress( $shippingAddress );


		$autoSettle = new AutoSettle();
		$autoSettle = $autoSettle->addAutoSettleFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE );

		$request = new PaymentRequest();
		$request
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addPaymentType( new PaymentType( PaymentType::AUTH ) )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( $autoSettle )
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
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using the helper methods with no enums.
	 */
	public function  testPaymentRequestXmlHelpersNoEnums() {
		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addCardType( new CardType( CardType::VISA ) )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = new TssInfo();

		$businessAddress = new Address();
		$businessAddress->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING );

		$tssInfo
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( $businessAddress )
			->addAddress( $shippingAddress );

		$autoSettle = new AutoSettle();
		$autoSettle = $autoSettle->addAutoSettleFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG->getRecurringFlag() )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE->getType() );

		$request = new PaymentRequest();
		$request
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( PaymentType::AUTH )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( $autoSettle )
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
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters.
	 */
	public function testPaymentRequestXmlSetters() {
		$card = new Card();
		$card->setExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE );
		$card->setNumber( SampleXmlValidationUtils::CARD_NUMBER );
		$card->setType( SampleXmlValidationUtils::$CARD_TYPE->getType() );
		$card->setCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME );
		$card->setIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$cvn = new Cvn();
		$cvn->setNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER );
		$cvn->setPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() );
		$card->setCvn( $cvn );

		$request = new PaymentRequest();
		$request->setAccount( SampleXmlValidationUtils::ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::MERCHANT_ID );
		$request->setType( PaymentType::AUTH );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::CURRENCY );
		$request->setAmount( $amount );

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() );

		$request->setAutoSettle( $autoSettle );
		$request->setCard( $card );
		$request->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP );
		$request->setChannel( SampleXmlValidationUtils::CHANNEL );
		$request->setOrderId( SampleXmlValidationUtils::ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::REQUEST_HASH );

		$comments = new CommentCollection();
		$comment  = new Comment();
		$comment->setId( 1 );
		$comment->setComment( SampleXmlValidationUtils::COMMENT1 );
		$comments->add( $comment );
		$comment = new Comment();
		$comment->setId( 2 );
		$comment->setComment( SampleXmlValidationUtils::COMMENT2 );
		$comments->add( $comment );
		$request->setComments( $comments );

		$request->setPaymentsReference( SampleXmlValidationUtils::PASREF );
		$request->setAuthCode( SampleXmlValidationUtils::AUTH_CODE );
		$request->setRefundHash( SampleXmlValidationUtils::REFUND_HASH );
		$request->setFraudFilter( SampleXmlValidationUtils::FRAUD_FILTER );

		$recurring = new Recurring();
		$recurring->setFlag( SampleXmlValidationUtils::$RECURRING_FLAG->getRecurringFlag() );
		$recurring->setSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() );
		$recurring->setType( SampleXmlValidationUtils::$RECURRING_TYPE->getType() );
		$request->setRecurring( $recurring );

		$tssInfo = new TssInfo();
		$tssInfo->setCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER );
		$tssInfo->setProductId( SampleXmlValidationUtils::PRODUCT_ID );
		$tssInfo->setVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE );
		$tssInfo->setCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP );

		$addresses = array();
		$address   = new Address();
		$address->setType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS->getAddressType() );
		$address->setCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS );
		$address->setCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );
		$addresses[] = $address;

		$address = new Address();
		$address->setType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING->getAddressType() );
		$address->setCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING );
		$address->setCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING );
		$addresses[] = $address;

		$tssInfo->setAddresses( $addresses );
		$request->setTssInfo( $tssInfo );

		$mpi = new Mpi();
		$mpi->setCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV );
		$mpi->setXid( SampleXmlValidationUtils::THREE_D_SECURE_XID );
		$mpi->setEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );
		$request->setMpi( $mpi );

		//convert to XML
		$xml = $request->toXml();

		//Convert from XML back to PaymentRequest
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentResponse} to and from XML.
	 */
	public function testPaymentResponseXml() {

		$response = new PaymentResponse();

		$response->setAccount( SampleXmlValidationUtils::ACCOUNT );
		$response->setAcquirerResponse( SampleXmlValidationUtils::ACQUIRER_RESPONSE );
		$response->setAuthCode( SampleXmlValidationUtils::AUTH_CODE );
		$response->setAuthTimeTaken( SampleXmlValidationUtils::AUTH_TIME_TAKEN );
		$response->setBatchId( SampleXmlValidationUtils::BATCH_ID );

		$cardIssuer = new CardIssuer();
		$cardIssuer->setBank( SampleXmlValidationUtils::BANK );
		$cardIssuer->setCountry( SampleXmlValidationUtils::COUNTRY );
		$cardIssuer->setCountryCode( SampleXmlValidationUtils::COUNTRY_CODE );
		$cardIssuer->setRegion( SampleXmlValidationUtils::REGION );
		$response->setCardIssuer( $cardIssuer );

		$response->setCvnResult( SampleXmlValidationUtils::CVN_RESULT );
		$response->setMerchantId( SampleXmlValidationUtils::MERCHANT_ID );
		$response->setMessage( SampleXmlValidationUtils::MESSAGE );
		$response->setOrderId( SampleXmlValidationUtils::ORDER_ID );
		$response->setPaymentsReference( SampleXmlValidationUtils::PASREF );
		$response->setResult( SampleXmlValidationUtils::RESULT_SUCCESS );
		$response->setHash( SampleXmlValidationUtils::RESPONSE_HASH );
		$response->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP );
		$response->setTimeTaken( SampleXmlValidationUtils::TIME_TAKEN );

		$tssResult = new TssResult();
		$tssResult->setResult( SampleXmlValidationUtils::TSS_RESULT );

		$checks = array();
		$check  = new TssResultCheck();
		$check->setId( SampleXmlValidationUtils::TSS_RESULT_CHECK1_ID );
		$check->setValue( SampleXmlValidationUtils::TSS_RESULT_CHECK1_VALUE );
		$checks[] = $check;
		$check    = new TssResultCheck();
		$check->setId( SampleXmlValidationUtils::TSS_RESULT_CHECK2_ID );
		$check->setValue( SampleXmlValidationUtils::TSS_RESULT_CHECK2_VALUE );
		$checks[] = $check;

		$tssResult->setChecks( $checks );
		$response->setTssResult( $tssResult );

		$response->setAvsAddressResponse( SampleXmlValidationUtils::AVS_ADDRESS );
		$response->setAvsPostcodeResponse( SampleXmlValidationUtils::AVS_POSTCODE );

		//marshal to XML
		$xml = $response->toXml();

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse, $this );
	}

	/**
	 * Tests conversion of {@link PaymentResponse} from XML file
	 */
	public function testPaymentResponseXmlFromFile() {
		$path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file.
	 */
	public function testPaymentRequestXmlFromFile() {

		$path   = SampleXmlValidationUtils::PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );


		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentResponse} from XML file with unknown element.
	 */
	public function  testPaymentResponseXmlFromFileUnknownElement() {
		$path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH_UNKNOWN_ELEMENT;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse, $this );

	}

	/**
	 * Test expected {@link RealexException} when unmarshalling invalid xml.
	 *
	 * @expectedException     com\realexpayments\remote\sdk\RealexException
	 */
	public function testFromXmlError() {

		//Try to unmarshal invalid XML
		XmlUtils::fromXml( "<xml>test</xml>xml>", new MessageType( MessageType::PAYMENT ) );
	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} to and from XML using the helper methods.
	 */
	public function testThreeDSecureRequestXmlHelpers() {

		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addCardType( new CardType( CardType::VISA ) )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$request = new ThreeDSecureRequest();
		$request
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( ThreeDSecureType::VERIFY_ENROLLED )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addTimestamp( SampleXmlValidationUtils::TIMESTAMP )
			->addOrderId( SampleXmlValidationUtils::ORDER_ID )
			->addHash( SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_HASH );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyEnrolledRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} to and from XML using the helper methods with no enums.
	 */
	public function testThreeDSecureRequestXmlHelpersNoEnums() {

		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addType( CardType::VISA )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$request = new ThreeDSecureRequest();
		$request
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( ThreeDSecureType::VERIFY_ENROLLED )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addTimestamp( SampleXmlValidationUtils::TIMESTAMP )
			->addOrderId( SampleXmlValidationUtils::ORDER_ID )
			->addHash( SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_HASH );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyEnrolledRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} verify enrolled to and from XML using setters.
	 */
	public function testThreeDSecureEnrolledRequestXmlWithSetters() {

		$card = new Card();
		$card->setExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE );
		$card->setNumber( SampleXmlValidationUtils::CARD_NUMBER );
		$card->setType( SampleXmlValidationUtils::$CARD_TYPE->getType() );
		$card->setCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME );
		$card->setIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$cvn = new Cvn();
		$cvn->setNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER );
		$cvn->setPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() );
		$card->setCvn( $cvn );

		$request = new ThreeDSecureRequest();
		$request->setAccount( SampleXmlValidationUtils::ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::MERCHANT_ID );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::CURRENCY );
		$request->setAmount( $amount );


		$request->setCard( $card );
		$request->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP );
		$request->setOrderId( SampleXmlValidationUtils::ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_HASH );
		$request->setType( ThreeDSecureType::VERIFY_ENROLLED );

		//convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyEnrolledRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} verify sig to and from XML using setters.
	 */
	public function testThreeDSecureSigRequestXmlWithSetters() {
		$card = new Card();
		$card->setExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE );
		$card->setNumber( SampleXmlValidationUtils::CARD_NUMBER );
		$card->setType( SampleXmlValidationUtils::$CARD_TYPE->getType() );
		$card->setCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME );
		$card->setIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$cvn = new Cvn();
		$cvn->setNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER );
		$cvn->setPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() );
		$card->setCvn( $cvn );

		$request = new ThreeDSecureRequest();
		$request->setAccount( SampleXmlValidationUtils::ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::MERCHANT_ID );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::CURRENCY );
		$request->setAmount( $amount );


		$request->setCard( $card );
		$request->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP );
		$request->setOrderId( SampleXmlValidationUtils::ORDER_ID );
		$request->setPares( SampleXmlValidationUtils::THREE_D_SECURE_PARES );
		$request->setHash( SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_SIG_REQUEST_HASH );
		$request->setType( ThreeDSecureType::VERIFY_SIG );

		//convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifySigRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link ThreeDSecureResponse} from XML file for verify enrolled
	 */
	public function testThreeDSecureEnrolledResponseXmlFromFile() {
		$path   = SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_RESPONSE_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var ThreeDSecureResponse $fromXmlResponse */
		$fromXmlResponse = new ThreeDSecureResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledThreeDSecureEnrolledResponse( $fromXmlResponse, $this );

	}

	/**
	 * Tests conversion of {@link ThreeDSecureResponse} from XML file for verify sig
	 */
	public function testThreeDSecureSigResponseXmlFromFile() {

		$path   = SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_SIG_RESPONSE_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var ThreeDSecureResponse $fromXmlResponse */
		$fromXmlResponse = new ThreeDSecureResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledThreeDSecureSigResponse( $fromXmlResponse, $this );

	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} from XML file for verify enrolled.
	 */
	public function testThreeDSecureRequestEnrolledXmlFromFile() {

		$path   = SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_ENROLLED_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var ThreeDSecureResponse $fromXmlResponse */
		$fromXmlResponse = new ThreeDSecureResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyEnrolledRequest( $fromXmlResponse, $this );

	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} from XML file for verify sig.
	 */
	public function testThreeDSecureRequestSigXmlFromFile() {

		$path   = SampleXmlValidationUtils::THREE_D_SECURE_VERIFY_SIG_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var ThreeDSecureRequest $fromXmlResponse */
		$fromXmlResponse = new ThreeDSecureRequest();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifySigRequest( $fromXmlResponse, $this );
	}

	/**
	 * Test expected {@link RealexException} when unmarshalling invalid xml.
	 *
	 * @expectedException     com\realexpayments\remote\sdk\RealexException
	 */
	public function testThreeDSecureFromXmlError() {

		//Try to unmarshal invalid XML
		XmlUtils::fromXml( "<xml>test</xml>xml>", new MessageType( MessageType::THREE_D_SECURE ) );
	}

	/**
	 * Tests that press indicator is sent correctly even when it is out of range
	 */
	public function  testPressIndicator() {
		$expectedCVN="5";

		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addCardType( new CardType( CardType::VISA ) )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( $expectedCVN )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = new TssInfo();

		$businessAddress = new Address();
		$businessAddress->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING );

		$tssInfo
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( $businessAddress )
			->addAddress( $shippingAddress );

		$autoSettle = new AutoSettle();
		$autoSettle = $autoSettle->addAutoSettleFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG->getRecurringFlag() )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE->getType() );

		$request = new PaymentRequest();
		$request
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( PaymentType::AUTH )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( $autoSettle )
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
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );


		$this->assertEquals( $expectedCVN, $fromXmlRequest->getCard()->getCvn()->getPresenceIndicator() );
	}
}
