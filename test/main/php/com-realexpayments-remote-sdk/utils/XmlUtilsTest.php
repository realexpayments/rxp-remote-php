<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\Country;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\domain\CvnNumber;
use com\realexpayments\remote\sdk\domain\DccInfo;
use com\realexpayments\remote\sdk\domain\DccInfoResult;
use com\realexpayments\remote\sdk\domain\Payer;
use com\realexpayments\remote\sdk\domain\PayerAddress;
use com\realexpayments\remote\sdk\domain\payment\Address;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;
use com\realexpayments\remote\sdk\domain\payment\CardIssuer;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;
use com\realexpayments\remote\sdk\domain\payment\FraudFilter;
use com\realexpayments\remote\sdk\domain\payment\Mpi;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentType;
use com\realexpayments\remote\sdk\domain\payment\ReasonCode;
use com\realexpayments\remote\sdk\domain\payment\Recurring;
use com\realexpayments\remote\sdk\domain\payment\RecurringFlag;
use com\realexpayments\remote\sdk\domain\payment\RecurringType;
use com\realexpayments\remote\sdk\domain\payment\TssInfo;
use com\realexpayments\remote\sdk\domain\payment\TssResult;
use com\realexpayments\remote\sdk\domain\payment\TssResultCheck;
use com\realexpayments\remote\sdk\domain\PaymentData;
use com\realexpayments\remote\sdk\domain\PhoneNumbers;
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
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
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
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER );


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
			->addFraudFilter( $fraudFilter )
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
	public function testPaymentRequestXmlHelpersNoEnums() {
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
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
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
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG->getRecurringFlag() )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE->getType() );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER );

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
			->addFraudFilter( $fraudFilter )
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
		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER );

		$request->setFraudFilter( $fraudFilter );

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
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for DCCInfo.
	 */
	public function testPaymentRequestXmlDCCInfoSetters() {

		$request = new PaymentRequest();
		$request->setAccount( SampleXmlValidationUtils::DCC_RATE_ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::DCC_RATE_MERCHANT_ID );
		$request->setType( PaymentType::DCC_RATE_LOOKUP );

		$card = new Card();
		$card->setExpiryDate( SampleXmlValidationUtils::DCC_RATE_CARD_EXPIRY_DATE );
		$card->setNumber( SampleXmlValidationUtils::DCC_RATE_CARD_NUMBER );
		$card->setType( SampleXmlValidationUtils::DCC_RATE_CARD_TYPE );
		$card->setCardHolderName( SampleXmlValidationUtils::DCC_RATE_CARD_HOLDER_NAME );
		$request->setCard( $card );

		$dccInfo = new DccInfo();
		$dccInfo->setDccProcessor( SampleXmlValidationUtils::DCC_RATE_CCP );
		$request->setDccInfo( $dccInfo );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::DCC_RATE_AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::DCC_RATE_CURRENCY );
		$request->setAmount( $amount );

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() );

		$request->setAutoSettle( $autoSettle );
		$request->setTimeStamp( SampleXmlValidationUtils::DCC_RATE_TIMESTAMP );

		$request->setOrderId( SampleXmlValidationUtils::DCC_RATE_ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::DCC_RATE_REQUEST_HASH );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDccRateLookUpPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for DCCInfo.
	 */
	public function testPaymentRequestXmlDCCInfoFluentSetters() {

		$request = new PaymentRequest();
		$request->addAccount( SampleXmlValidationUtils::DCC_RATE_ACCOUNT )
		        ->addMerchantId( SampleXmlValidationUtils::DCC_RATE_MERCHANT_ID )
		        ->addType( PaymentType::DCC_RATE_LOOKUP );

		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::DCC_RATE_CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::DCC_RATE_CARD_NUMBER )
		     ->addType( SampleXmlValidationUtils::DCC_RATE_CARD_TYPE )
		     ->addCardHolderName( SampleXmlValidationUtils::DCC_RATE_CARD_HOLDER_NAME );

		$request->addCard( $card );

		$dccInfo = new DccInfo();
		$dccInfo->addDccProcessor( SampleXmlValidationUtils::DCC_RATE_CCP );
		$request->addDccInfo( $dccInfo );

		$request->addAmount( SampleXmlValidationUtils::DCC_RATE_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::DCC_RATE_CURRENCY );

		$autoSettle = new AutoSettle();
		$autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() );

		$request->addAutoSettle( $autoSettle )
		        ->addTimeStamp( SampleXmlValidationUtils::DCC_RATE_TIMESTAMP )
		        ->addOrderId( SampleXmlValidationUtils::DCC_RATE_ORDER_ID )
		        ->addHash( SampleXmlValidationUtils::DCC_RATE_REQUEST_HASH );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDccRateLookUpPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for DCCInfo.
	 */
	public function testPaymentRequestXmlDCCAuthSetters() {

		$request = new PaymentRequest();
		$request->setAccount( SampleXmlValidationUtils::DCC_AUTH_ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::DCC_AUTH_MERCHANT_ID );

		$card = new Card();
		$card->setExpiryDate( SampleXmlValidationUtils::DCC_AUTH_CARD_EXPIRY_DATE );
		$card->setNumber( SampleXmlValidationUtils::DCC_AUTH_CARD_NUMBER );
		$card->setType( SampleXmlValidationUtils::DCC_AUTH_CARD_TYPE );
		$card->setCardHolderName( SampleXmlValidationUtils::DCC_AUTH_CARD_HOLDER_NAME );
		$request->setCard( $card );

		$dccAmount = new Amount();
		$dccAmount->setAmount( SampleXmlValidationUtils:: DCC_AUTH_CH_AMOUNT );
		$dccAmount->setCurrency( SampleXmlValidationUtils::DCC_AUTH_CH_CURRENCY );

		$dccInfo = new DccInfo();
		$dccInfo->setDccProcessor( SampleXmlValidationUtils::DCC_AUTH_CCP );
		$dccInfo->setRate( SampleXmlValidationUtils::DCC_AUTH_RATE );
		$dccInfo->setAmount( $dccAmount );
		$request->setDccInfo( $dccInfo );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::DCC_AUTH_AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::DCC_AUTH_CURRENCY );
		$request->setAmount( $amount );

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() );
		$request->setAutoSettle( $autoSettle );

		$request->setTimeStamp( SampleXmlValidationUtils::DCC_AUTH_TIMESTAMP );

		$request->setOrderId( SampleXmlValidationUtils::DCC_AUTH_ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::DCC_AUTH_REQUEST_HASH );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDccAuthLookUpPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for Payer.
	 */
	public function testPaymentRequestXmlPayerSetters() {

		$request = new PaymentRequest();
		$request->setAccount( SampleXmlValidationUtils::PAYER_NEW_ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::PAYER_NEW_MERCHANT_ID );
		$request->setType( PaymentType::PAYER_NEW );

		$payer = new Payer();
		$payer->setType( SampleXmlValidationUtils::PAYER_NEW_PAYER_TYPE );
		$payer->setRef( SampleXmlValidationUtils::PAYER_NEW_PAYER_REF );
		$payer->setTitle( SampleXmlValidationUtils::PAYER_NEW_PAYER_TITLE );
		$payer->setFirstName( SampleXmlValidationUtils::PAYER_NEW_PAYER_FIRSTNAME );
		$payer->setSurname( SampleXmlValidationUtils::PAYER_NEW_PAYER_SURNAME );
		$payer->setCompany( SampleXmlValidationUtils::PAYER_NEW_PAYER_COMPANY );
		$payer->setEmail( SampleXmlValidationUtils::PAYER_NEW_PAYER_EMAIL );

		$address = new PayerAddress();
		$address->setLine1( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_LINE_1 );
		$address->setLine2( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_LINE_2 );
		$address->setLine3( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_LINE_3 );
		$address->setCity( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_CITY );
		$address->setCounty( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_COUNTY );
		$address->setPostcode( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_POSTCODE );

		$country = new Country();
		$country->setCode( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_COUNTRY_CODE );
		$country->setName( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_COUNTRY_NAME );

		$address->setCountry( $country );

		$payer->setAddress( $address );
		$payer->addComment( SampleXmlValidationUtils::PAYER_NEW_PAYER_COMMENT_1 );
		$payer->addComment( SampleXmlValidationUtils::PAYER_NEW_PAYER_COMMENT_2 );

		$phoneNumbers = new PhoneNumbers();
		$phoneNumbers->setHomePhoneNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_HOME_NUMBER );
		$phoneNumbers->setWorkPhoneNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_WORK_NUMBER );
		$phoneNumbers->setFaxPhoneNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_FAX_NUMBER );
		$phoneNumbers->setMobilePhoneNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_MOBILE_NUMBER );

		$payer->setPhoneNumbers( $phoneNumbers );

		$request->setPayer( $payer );

		$request->setTimeStamp( SampleXmlValidationUtils::PAYER_NEW_TIMESTAMP );

		$request->setOrderId( SampleXmlValidationUtils::PAYER_NEW_ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::PAYER_NEW_REQUEST_HASH );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPayerNewPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for Payer.
	 */
	public function testPaymentRequestXmlPayerFluentSetters() {

		$request = new PaymentRequest();
		$request->addAccount( SampleXmlValidationUtils::PAYER_NEW_ACCOUNT )
		        ->addMerchantId( SampleXmlValidationUtils::PAYER_NEW_MERCHANT_ID )
		        ->addType( PaymentType::PAYER_NEW );

		$payer = new Payer();
		$payer->addType( SampleXmlValidationUtils::PAYER_NEW_PAYER_TYPE )
		      ->addRef( SampleXmlValidationUtils::PAYER_NEW_PAYER_REF )
		      ->addTitle( SampleXmlValidationUtils::PAYER_NEW_PAYER_TITLE )
		      ->addFirstName( SampleXmlValidationUtils::PAYER_NEW_PAYER_FIRSTNAME )
		      ->addSurname( SampleXmlValidationUtils::PAYER_NEW_PAYER_SURNAME )
		      ->addCompany( SampleXmlValidationUtils::PAYER_NEW_PAYER_COMPANY )
		      ->addEmail( SampleXmlValidationUtils::PAYER_NEW_PAYER_EMAIL );

		$address = new PayerAddress();
		$address->addLine1( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_LINE_1 )
		        ->addLine2( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_LINE_2 )
		        ->addLine3( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_LINE_3 )
		        ->addCity( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_CITY )
		        ->addCounty( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_COUNTY )
		        ->addPostcode( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_POSTCODE )
		        ->addCountryCode( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_COUNTRY_CODE )
		        ->addCountryName( SampleXmlValidationUtils::PAYER_NEW_PAYER_ADDRESS_COUNTRY_NAME );


		$payer->addAddress( $address )
		      ->addComment( SampleXmlValidationUtils::PAYER_NEW_PAYER_COMMENT_1 )
		      ->addComment( SampleXmlValidationUtils::PAYER_NEW_PAYER_COMMENT_2 )
		      ->addMobileNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_HOME_NUMBER )
		      ->addWorkPhoneNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_WORK_NUMBER )
		      ->addFaxPhoneNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_FAX_NUMBER )
		      ->addMobileNumber( SampleXmlValidationUtils::PAYER_NEW_PAYER_MOBILE_NUMBER );

		$request->addPayer( $payer )
		        ->addTimeStamp( SampleXmlValidationUtils::PAYER_NEW_TIMESTAMP )
		        ->addOrderId( SampleXmlValidationUtils::PAYER_NEW_ORDER_ID )
		        ->addHash( SampleXmlValidationUtils::PAYER_NEW_REQUEST_HASH );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPayerNewPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for Paymentdata.
	 */
	public function testPaymentRequestXmlPaymentDataSetters() {

		$cvnNumber = new CvnNumber();
		$cvnNumber->setNumber( SampleXmlValidationUtils::RECEIPT_IN_CVN );

		$paymentData = new PaymentData();
		$paymentData->setCvnNumber( $cvnNumber );

		$request = new PaymentRequest();
		$request->setAccount( SampleXmlValidationUtils::RECEIPT_IN_ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::RECEIPT_IN_MERCHANT_ID );
		$request->setType( PaymentType::RECEIPT_IN );
		$request->setPaymentData($paymentData);

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::RECEIPT_IN_AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::RECEIPT_IN_CURRENCY );
		$request->setAmount( $amount );

		$request->setPayerRef( SampleXmlValidationUtils::RECEIPT_IN_PAYER );
		$request->setPaymentMethod( SampleXmlValidationUtils::RECEIPT_IN_PAYMENT_METHOD );

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$RECEIPT_IN_AUTO_SETTLE_FLAG->getFlag() );
		$request->setAutoSettle( $autoSettle );

		$request->setTimeStamp( SampleXmlValidationUtils::RECEIPT_IN_TIMESTAMP );

		$request->setOrderId( SampleXmlValidationUtils::RECEIPT_IN_ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::RECEIPT_IN_REQUEST_HASH );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledReceiptInPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for Paymentdata.
	 */
	public function testPaymentRequestXmlPaymentDataFluentSetters1() {

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$RECEIPT_IN_AUTO_SETTLE_FLAG->getFlag() );

		$cvnNumber = new CvnNumber();
		$cvnNumber->addNumber( SampleXmlValidationUtils::RECEIPT_IN_CVN );

		$paymentData = new PaymentData();
		$paymentData->addCvnNumber( $cvnNumber );

		$request = new PaymentRequest();
		$request->addAccount( SampleXmlValidationUtils::RECEIPT_IN_ACCOUNT )
		        ->addMerchantId( SampleXmlValidationUtils::RECEIPT_IN_MERCHANT_ID )
		        ->addType( PaymentType::RECEIPT_IN )
		        ->addAmount( SampleXmlValidationUtils::RECEIPT_IN_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::RECEIPT_IN_CURRENCY )
		        ->addPayerReference( SampleXmlValidationUtils::RECEIPT_IN_PAYER )
		        ->addPaymentMethod( SampleXmlValidationUtils::RECEIPT_IN_PAYMENT_METHOD )
		        ->addAutoSettle( $autoSettle )
		        ->addTimeStamp( SampleXmlValidationUtils::RECEIPT_IN_TIMESTAMP )
		        ->addOrderId( SampleXmlValidationUtils::RECEIPT_IN_ORDER_ID )
		        ->addHash( SampleXmlValidationUtils::RECEIPT_IN_REQUEST_HASH )
		        ->addPaymentData( $paymentData );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledReceiptInPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters for Paymentdata.
	 */
	public function testPaymentRequestXmlPaymentDataFluentSetters2() {

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$RECEIPT_IN_AUTO_SETTLE_FLAG->getFlag() );

		$paymentData = new PaymentData();
		$paymentData->addCvnNumber( SampleXmlValidationUtils::RECEIPT_IN_CVN);

		$request = new PaymentRequest();
		$request->addAccount( SampleXmlValidationUtils::RECEIPT_IN_ACCOUNT )
		        ->addMerchantId( SampleXmlValidationUtils::RECEIPT_IN_MERCHANT_ID )
		        ->addType( PaymentType::RECEIPT_IN )
		        ->addAmount( SampleXmlValidationUtils::RECEIPT_IN_AMOUNT )
		        ->addCurrency( SampleXmlValidationUtils::RECEIPT_IN_CURRENCY )
		        ->addPayerReference( SampleXmlValidationUtils::RECEIPT_IN_PAYER )
		        ->addPaymentMethod( SampleXmlValidationUtils::RECEIPT_IN_PAYMENT_METHOD )
		        ->addAutoSettle( $autoSettle )
		        ->addTimeStamp( SampleXmlValidationUtils::RECEIPT_IN_TIMESTAMP )
		        ->addOrderId( SampleXmlValidationUtils::RECEIPT_IN_ORDER_ID )
		        ->addHash( SampleXmlValidationUtils::RECEIPT_IN_REQUEST_HASH )
		        ->addPaymentData( $paymentData );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledReceiptInPaymentRequest( $fromXmlRequest, $this );
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
		$response->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP_RESPONSE );
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
	 * Tests conversion of {@link PaymentResponse} to and from XML.
	 */

	public function testPaymentResponseDccInfoXml() {
		$response = new PaymentResponse();

		$response->setAccount( SampleXmlValidationUtils::DCC_RATE_ACCOUNT_RESPONSE );
		$response->setResult( SampleXmlValidationUtils::DCC_RATE_RESULT_RESPONSE );

		$response->setCvnResult( SampleXmlValidationUtils::DCC_RATE_CVN_RESULT_RESPONSE );
		$response->setMerchantId( SampleXmlValidationUtils::DCC_RATE_MERCHANT_ID_RESPONSE );
		$response->setOrderId( SampleXmlValidationUtils::DCC_RATE_ORDER_ID_RESPONSE );
		$response->setPaymentsReference( SampleXmlValidationUtils::DCC_RATE_PASREF_RESPONSE );
		$response->setHash( SampleXmlValidationUtils::DCC_RATE_REQUEST_HASH_RESPONSE );
		$response->setTimeStamp( SampleXmlValidationUtils::DCC_RATE_TIMESTAMP_RESPONSE );

		$dccInfoResult = new DccInfoResult();
		$dccInfoResult->setCardHolderAmount( SampleXmlValidationUtils::DCC_RATE_CH_AMOUNT_RESPONSE );
		$dccInfoResult->setCardHolderCurrency( SampleXmlValidationUtils::DCC_RATE_CH_CURRENCY_RESPONSE );
		$dccInfoResult->setCardHolderRate( SampleXmlValidationUtils::DCC_RATE_CH_RATE_RESPONSE );
		$dccInfoResult->setMerchantAmount( SampleXmlValidationUtils::DCC_RATE_MERCHANT_AMOUNT_RESPONSE );
		$dccInfoResult->setMerchantCurrency( SampleXmlValidationUtils::DCC_RATE_MERCHANT_CURRENCY_RESPONSE );

		$response->setDccInfoResult( $dccInfoResult );


		// convert to XML
		$xml = $response->toXml();

		// Convert from XML back to PaymentResponse
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDCCPaymentResponse( $fromXmlResponse, $this );
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
	 * Tests conversion of {@link PaymentResponse} from XML file
	 */

	public function testPaymentResponseXmlDccInfoFromFile() {

		$path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_DCC_INFO_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDCCPaymentResponse( $fromXmlResponse, $this );
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
	public function testPaymentResponseXmlFromFileUnknownElement() {
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
	 * @expectedException   \com\realexpayments\remote\sdk\RealexException
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
	 * Tests conversion of {@link ThreeDSecureRequest} card enrolled to and from XML using setters.
	 */

	public function testThreeDSecureCardEnrolledRequestXmlWithSetters() {

		$request = new ThreeDSecureRequest();
		$request->setAccount( SampleXmlValidationUtils::CARD_VERIFY_ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::CARD_VERIFY_MERCHANT_ID );


		$paymentData = new PaymentData();
		$paymentData->addCvnNumber( SampleXmlValidationUtils::CARD_PAYMENT_DATA_CVN );
		$request->setPaymentData( $paymentData );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::CARD_VERIFY_AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::CARD_VERIFY_CURRENCY );
		$request->setAmount( $amount );


		$request->setTimeStamp( SampleXmlValidationUtils::CARD_VERIFY_TIMESTAMP );
		$request->setOrderId( SampleXmlValidationUtils::CARD_VERIFY_ORDER_ID );
		$request->setPaymentMethod( SampleXmlValidationUtils::CARD_VERIFY_REF );
		$request->setPayerRef( SampleXmlValidationUtils::CARD_VERIFY_PAYER_REF );
		$request->setHash( SampleXmlValidationUtils::CARD_VERIFY_REQUEST_HASH );
		$request->setType( ThreeDSecureType::VERIFY_STORED_CARD_ENROLLED );

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$CARD_VERIFY_AUTO_SETTLE_FLAG->getFlag() );

		$request->setAutoSettle( $autoSettle );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyCardEnrolledPaymentRequest( $fromXmlRequest, $this );
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
		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyEnrolledRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link ThreeDSecureRequest} from XML file for verify sig.
	 */
	public function testThreeDSecureRequestCardEnrolledFromFile() {

		$path   = SampleXmlValidationUtils::CARD_VERIFY_ENROLLED_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var ThreeDSecureRequest $fromXmlRequest */
		$fromXmlRequest = new ThreeDSecureRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVerifyCardEnrolledPaymentRequest( $fromXmlRequest, $this );
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
	 * @expectedException     \com\realexpayments\remote\sdk\RealexException
	 */
	public function testThreeDSecureFromXmlError() {

		//Try to unmarshal invalid XML
		XmlUtils::fromXml( "<xml>test</xml>xml>", new MessageType( MessageType::THREE_D_SECURE ) );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for mobile-auth payment types.
	 *
	 */
	public function testPaymentRequestXmlFromFileMobileAuth() {

		$path   = SampleXmlValidationUtils::MOBILE_AUTH_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledMobileAuthPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for settle payment types.
	 */
	public function testPaymentRequestXmlFromFileSettle() {

		$path   = SampleXmlValidationUtils::SETTLE_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledSettlePaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for void payment types.
	 */
	public function testPaymentRequestXmlFromFileVoid() {

		$path   = SampleXmlValidationUtils::VOID_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledVoidPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for rebate payment types.
	 */
	public function testPaymentRequestXmlFromFileRebate() {

		$path   = SampleXmlValidationUtils::REBATE_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledRebatePaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for OTB payment types.
	 */
	public function testPaymentRequestXmlFromFileOtb() {

		$path   = SampleXmlValidationUtils::OTB_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledOtbPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for credit payment types.
	 */
	public function testPaymentRequestXmlFromFileCredit() {

		$path   = SampleXmlValidationUtils::CREDIT_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledCreditPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for hold payment types.
	 */
	public function testPaymentRequestXmlFromFileHold() {

		$path   = SampleXmlValidationUtils::HOLD_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledHoldPaymentRequest( $fromXmlRequest, $this );

	}
	

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for release payment types.
	 */
	public function testPaymentRequestXmlFromFileRelease() {


		$path   = SampleXmlValidationUtils::RELEASE_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledReleasePaymentRequest( $fromXmlRequest, $this );
	}


	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for receipt-in payment types.
	 */
	public function testPaymentRequestXmlFromFileReceiptIn() {

		$path   = SampleXmlValidationUtils::RECEIPT_IN_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledReceiptInPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for receipt-in payment types.
	 */
	public function testPaymentRequestXmlFromFilePaymentOut() {

		$path   = SampleXmlValidationUtils::PAYMENT_OUT_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentOutPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFilePayerNew() {

		$path   = SampleXmlValidationUtils::PAYER_NEW_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPayerNewPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFilePayerEdit() {

		$path   = SampleXmlValidationUtils::PAYER_EDIT_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPayerEditPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFileCardNew() {

		$path   = SampleXmlValidationUtils::CARD_NEW_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledCardAddPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFileCardEditReplaceCard() {

		$path   = SampleXmlValidationUtils::CARD_EDIT_REPLACE_CARD_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledCardEditReplaceCardPaymentRequest( $fromXmlRequest, $this );
	}


	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFileCardEditReplaceIssueNo() {

		$path   = SampleXmlValidationUtils::CARD_EDIT_UPDATE_ISSUE_NO_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledCardEditReplaceIssueNoPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFileCardEditReplaceCHName() {

		$path   = SampleXmlValidationUtils::CARD_EDIT_UPDATE_CH_NAME_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledCardEditReplaceCHNamePaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for payer-new payment types.
	 */
	public function testPaymentRequestXmlFromFileCardDelete() {

		$path   = SampleXmlValidationUtils::CARD_DELETE_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledCardDeletePaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for dcc rate lookup payment types.
	 */
	public function testPaymentRequestXmlFromFileDccLookup() {

		$path   = SampleXmlValidationUtils::DCC_RATE_LOOKUP_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDccRateLookUpPaymentRequest( $fromXmlRequest, $this );
	}	

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for dcc auth payment types.
	 */
	public function testPaymentRequestXmlFromFileDccAuth() {

		$path   = SampleXmlValidationUtils::DCC_RATE_AUTH_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDccAuthLookUpPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for receipt-in payment types.
	 */
	public function testPaymentRequestXmlFromFileReceiptInOTB() {

		$path   = SampleXmlValidationUtils::RECEIPT_IN_OTB_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledReceiptInOTBPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests that press indicator is sent correctly even when it is out of range
	 */
	public function testPressIndicator() {
		$expectedCVN = "5";

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
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
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
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG->getRecurringFlag() )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE->getType() );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER );

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
			->addFraudFilter( $fraudFilter )
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

	/**
	 * Tests and invalid recurring flag value
	 */
	public function testInvalidRecurringFlag() {


		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addCardType( new CardType( CardType::VISA ) )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = new TssInfo();

		$businessAddress = new Address();
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
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
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$expectedFlag  = "3";
		$recurringFlag = new RecurringFlag( $expectedFlag );

		$recurring = new Recurring();
		$recurring->addFlag( $recurringFlag )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE->getType() );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER);


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
			->addFraudFilter( $fraudFilter )
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );


		$this->assertEquals( $expectedFlag, $fromXmlRequest->getRecurring()->getFlag() );
	}

	/**
	 * Tests and invalid recurring type value
	 */
	public function testInvalidRecurringType() {


		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addCardType( new CardType( CardType::VISA ) )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = new TssInfo();

		$businessAddress = new Address();
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
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
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$expectedType  = "badValue";
		$recurringType = new RecurringType( $expectedType );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG->getRecurringFlag() )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE->getSequence() )
		          ->addType( $recurringType );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER);

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
			->addFraudFilter( $fraudFilter )
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );


		$this->assertEquals( $expectedType, $fromXmlRequest->getRecurring()->getType() );
	}

	/**
	 * Tests symbols on comments fields
	 */
	public function testSymbolsOnCommentsAndTSS() {
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
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING );
		$tssInfo
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER_WITH_SYMBOLS )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE_WITH_SYMBOLS )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( $businessAddress )
			->addAddress( $shippingAddress );


		$autoSettle = new AutoSettle();
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );

		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER);


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
			->addComment( SampleXmlValidationUtils::COMMENT1_WITH_SYMBOLS )
			->addComment( SampleXmlValidationUtils::COMMENT2_WITH_SYMBOLS )
			->addPaymentsReference( SampleXmlValidationUtils::PASREF )
			->addAuthCode( SampleXmlValidationUtils::AUTH_CODE )
			->addRefundHash( SampleXmlValidationUtils::REFUND_HASH )
			->addFraudFilter( $fraudFilter )
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequestWithSymbols( $fromXmlRequest, $this );
	}

	/**
	 * Tests symbols on comments fields from file
	 */
	public function testSymbolsOnCommentsAndTSSFromFile() {

		$path   = SampleXmlValidationUtils::PAYMENT_REQUEST_WITH_SYMBOLS_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );


		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequestWithSymbols( $fromXmlRequest, $this );
	}

	/**
	 * Tests and invalid currency value (null)
	 */
	public function testNullValueOnCurrency() {


		$card = new Card();
		$card->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
		     ->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
		     ->addCardType( new CardType( CardType::VISA ) )
		     ->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
		     ->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
		     ->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE )
		     ->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = new TssInfo();

		$businessAddress = new Address();
		$businessAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
		                ->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
		                ->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );

		$shippingAddress = new Address();
		$shippingAddress->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
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
		$autoSettle = $autoSettle->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG );

		$mpi = new Mpi();
		$mpi->addCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV )
		    ->addXid( SampleXmlValidationUtils::THREE_D_SECURE_XID )
		    ->addEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );


		$recurring = new Recurring();
		$recurring->addFlag( SampleXmlValidationUtils::$RECURRING_FLAG )
		          ->addSequence( SampleXmlValidationUtils::$RECURRING_SEQUENCE )
		          ->addType( SampleXmlValidationUtils::$RECURRING_TYPE );

		$fraudFilter = new FraudFilter();
		$fraudFilter->addMode(SampleXmlValidationUtils::$FRAUD_FILTER);


		$expectedCurrency = null;
		$request          = new PaymentRequest();
		$request
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( PaymentType::AUTH )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( $expectedCurrency )
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
			->addFraudFilter( $fraudFilter )
			->addRecurring( $recurring )
			->addTssInfo( $tssInfo )
			->addMpi( $mpi );


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );


		$this->assertEquals( $expectedCurrency, $fromXmlRequest->getAmount()->getCurrency() );
	}

	/**
	 * Tests conversion of {@link PaymentResponse} to and from XML.
	 */
	public function testPaymentResponseXmlAndDeserialize() {

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
		$response->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP_RESPONSE );
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

		$xmlResponse = $fromXmlResponse->toXML();
		/* @var PaymentResponse $fromXmlResponse2 */
		$fromXmlResponse2 = new PaymentResponse();
		$fromXmlResponse2 = $fromXmlResponse2->fromXml( $xmlResponse );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse2, $this );
	}

	/**
	 * Tests conversion of {@link PaymentResponse} to and from XML.
	 */
	public function testPaymentResponseXmlAndDeserializeOverallResults() {

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
		$response->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP_RESPONSE );
		$response->setTimeTaken( SampleXmlValidationUtils::TIME_TAKEN );

		$tssResult = new TssResult();
		$tssResult->setResult( SampleXmlValidationUtils::TSS_RESULT );

		$response->setTssResult( $tssResult );

		$response->setAvsAddressResponse( SampleXmlValidationUtils::AVS_ADDRESS );
		$response->setAvsPostcodeResponse( SampleXmlValidationUtils::AVS_POSTCODE );

		//marshal to XML
		$xml = $response->toXml();

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse, $this, true );
	}


	/**
	 * Tests Fraud Code Request Type
	 */
	public function testPaymentRequestFraudReasonCodeXmlFromFile() {
		$path   = SampleXmlValidationUtils::HOLD_PAYMENT_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentRequest();
		$fromXmlResponse = $fromXmlResponse->fromXML( $xml );
		SampleXmlValidationUtils::checkUnmarshalledRequestCodeResponse( $fromXmlResponse, $this , ReasonCode::FRAUD);
	}

	/**
	 * Tests all reasons code cases
	 */
	public function testPaymentRequestAllReasonCodeXmlFromCode() {
		$paymentRequest = new PaymentRequest();
		$paymentRequest->addAccount(SampleXmlValidationUtils::HOLD_ACCOUNT);
		$paymentRequest->addMerchantId(SampleXmlValidationUtils::HOLD_MERCHANT_ID);
		$paymentRequest->addTimestamp(SampleXmlValidationUtils::HOLD_TIMESTAMP);
		$paymentRequest->addOrderId(SampleXmlValidationUtils::HOLD_ORDER_ID);
		$paymentRequest->addHash(SampleXmlValidationUtils::HOLD_REQUEST_HASH);
		$paymentRequest->addType(PaymentType::HOLD);

		$reasons = array(
			ReasonCode::FRAUD,
			ReasonCode::FALSE_POSITIVE,
			ReasonCode::IN_STOCK,
			ReasonCode::NOT_GIVEN,
			ReasonCode::OTHER,
			ReasonCode::OUT_OF_STOCK
		);

		foreach ($reasons as $reason) {
			$paymentRequest->setReasonCode($reason);

			//marshal to XML
			$xml = $paymentRequest->toXml();

			//unmarshal back to response
			/* @var PaymentResponse $fromXmlResponse */
			$fromXmlResponse = new PaymentRequest();
			$fromXmlResponse = $fromXmlResponse->fromXML($xml);
			SampleXmlValidationUtils::checkUnmarshalledRequestCodeResponse($fromXmlResponse, $this,$reason);
		}
	}

	/**
	 * Tests Fake reason code
	 */
	public function testPaymentRequestCodeXmlFromCodeFailed() {
		$paymentRequest = new PaymentRequest();
		$paymentRequest->addAccount(SampleXmlValidationUtils::HOLD_ACCOUNT);
		$paymentRequest->addMerchantId(SampleXmlValidationUtils::HOLD_MERCHANT_ID);
		$paymentRequest->addTimestamp(SampleXmlValidationUtils::HOLD_TIMESTAMP);
		$paymentRequest->addOrderId(SampleXmlValidationUtils::HOLD_ORDER_ID);
		$paymentRequest->addHash(SampleXmlValidationUtils::HOLD_REQUEST_HASH);
		$paymentRequest->addType(PaymentType::HOLD);
		$paymentRequest->addReasonCode('fake reason');

		$reasons = array(
			ReasonCode::FRAUD,
			ReasonCode::FALSE_POSITIVE,
			ReasonCode::IN_STOCK,
			ReasonCode::NOT_GIVEN,
			ReasonCode::OTHER,
			ReasonCode::OUT_OF_STOCK
		);

		foreach ($reasons as $reason) {

			//marshal to XML
			$xml = $paymentRequest->toXml();

			//unmarshal back to response
			/* @var PaymentResponse $fromXmlResponse */
			$fromXmlResponse = new PaymentRequest();
			$fromXmlResponse = $fromXmlResponse->fromXML( $xml );
			SampleXmlValidationUtils::checkUnmarshalledRequestCodeResponse( $fromXmlResponse, $this,$reason,false );
		}
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for stored card dcc rate payment types.
	 */
	public function testPaymentRequestXmlFromFileDccStoredCard() {
		$path   = SampleXmlValidationUtils::DCC_STORED_CARD_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledDccStoreCardPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for hold payment types.
	 */
	public function testPaymentRequestXmlFromFileHoldReasonCodeHold() {

		$path   = SampleXmlValidationUtils::HOLD_PAYMENT_REASON_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledHoldReasonHoldPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for false positive reason code.
	 */
	public function testPaymentRequestXmlFromFileFalsePositiveReasonCodeHold() {

		$path   = SampleXmlValidationUtils::HOLD_PAYMENT_REASON_FALSE_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledHoldReasonHoldPaymentRequest( $fromXmlRequest, $this );

	}
	/**
	 * Tests conversion of {@link PaymentRequest} from XML file for release payment types.
	 */
	public function testPaymentRequestXmlFromFileHoldReasonCodeRelease() {

		$path   = SampleXmlValidationUtils::RELEASE_PAYMENT_REASON_REQUEST_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to request
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = new PaymentRequest();
		$fromXmlRequest = $fromXmlRequest->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledHoldReasonRequestPaymentRequest( $fromXmlRequest, $this );

	}

	/**
	 * Tests conversion of {@link PaymentResponse} from XML file
	 */
	public function testPaymentResponseWithFraudFilterXmlFromFile() {
		$path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_WITH_FRAUD_FILTER_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml    = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = new PaymentResponse();
		$fromXmlResponse = $fromXmlResponse->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponseWithFraudFilter( $fromXmlResponse, $this );
	}

    /**
     * Tests conversion of {@link PaymentResponse} from XML file
     */
    public function testPaymentResponseWithFraudFilterNoRulesXmlFromFile() {
        $path   = SampleXmlValidationUtils::PAYMENT_RESPONSE_WITH_FRAUD_FILTER_NO_RULES_XML_PATH;
        $prefix = __DIR__ . '/../../../resources';
        $xml    = file_get_contents( $prefix . $path );

        //unmarshal back to response
        /* @var PaymentResponse $fromXmlResponse */
        $fromXmlResponse = new PaymentResponse();
        $fromXmlResponse = $fromXmlResponse->fromXml( $xml );
        SampleXmlValidationUtils::checkUnmarshalledPaymentResponseWithFraudFilterNoRules( $fromXmlResponse, $this );
    }

}
