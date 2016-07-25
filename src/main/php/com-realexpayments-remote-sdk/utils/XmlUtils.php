<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\iRequest;
use com\realexpayments\remote\sdk\domain\payment\normaliser\AddressNormaliser;
use com\realexpayments\remote\sdk\domain\payment\normaliser\AmountNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\AutoSettleNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\CardNormaliser;
use com\realexpayments\remote\sdk\domain\payment\normaliser\CommentsNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\CountryNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\CustomStringXmlEncoder;
use com\realexpayments\remote\sdk\domain\payment\normaliser\CvnNormaliser;
use com\realexpayments\remote\sdk\domain\payment\normaliser\CvnNumberNormaliser;
use com\realexpayments\remote\sdk\domain\payment\normaliser\DccInfoNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\DccInfoResultNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\FraudFilterNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\FraudFilterRuleCollectionNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\FraudFilterRuleNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\PayerAddressNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\PayerNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\PaymentDataNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\PaymentRequestNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\PaymentResponseNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\PhoneNumbersNormalizer;
use com\realexpayments\remote\sdk\domain\payment\normaliser\TssCheckNormaliser;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\threeDSecure\normaliser\ThreeDSecureRequestNormalizer;
use com\realexpayments\remote\sdk\domain\threeDSecure\normaliser\ThreeDSecureResponseNormalizer;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureRequest;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureResponse;
use com\realexpayments\remote\sdk\RealexException;
use com\realexpayments\remote\sdk\RXPLogger;
use Exception;
use Logger;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * XML helper class. Marshals/unmarshals XML.
 *
 * @author vicpada
 */
class XmlUtils {


	/**
	 * @var Logger logger
	 */
	private static $logger;

	private static $initialised = false;


	/**
	 * @var Serializer[] marshallers
	 */
	private static $marshallers;


	/**
	 * Marshals object to XML
	 *
	 * @param object $object
	 * @param MessageType $messageType
	 *
	 * @return string
	 */
	public static function toXml( $object, MessageType $messageType ) {

		self::Initialise();

		self::$logger->debug( "Marshalling domain object to XML" );

		$xml = null;

		try {

			$rootName = self::getRootName( $object );
			$xml      = self::$marshallers[ $messageType->getType() ]->serialize( $object, 'xml', array(
				'xml_root_node_name' => $rootName,
				'xml_format_output'  => true
			) );

		} catch ( Exception $e ) {

			self::$logger->error( "Error unmarshalling to XML. " . $e );
			throw new RealexException( "Error unmarshalling to XML", $e );
		}

		return $xml;
	}


	/**
	 * @param string $xml
	 *
	 * @param MessageType $messageType
	 *
	 * @return object
	 */
	public static function fromXml( $xml, MessageType $messageType ) {
		self::Initialise();

		self::$logger->debug( "Unmarshalling XML to domain object" );
		$object = null;

		try {

			// TODO: Obtain type
			$object = self::$marshallers[ $messageType->getType() ]
				->deserialize( $xml, self::getClassName( $xml, $messageType ), 'xml' );

		} catch ( Exception $e ) {
			self::$logger->error( "Error unmarshalling from XML. " . $e );
			throw new RealexException( "Error unmarshalling from XML.", $e );
		}

		return $object;
	}

	private static function Initialise() {
		if ( self::$initialised ) {
			return;
		}

		self::$logger = RXPLogger::getLogger( __CLASS__ );

		self::InitialiseMarshaller();

		self::$initialised = true;
	}

	private static function InitialiseMarshaller() {

		self::$marshallers = array();

		$encoders                                  = array(
			new CustomStringXmlEncoder( 'response', array(
				'timestamp',
				'number'
			) )
		);
		$normalizers                               = array(
			new PaymentRequestNormalizer(),
			new PaymentResponseNormalizer(),
			new AddressNormaliser(),
			new CommentsNormalizer(),
			new TssCheckNormaliser(),
			new CountryNormalizer(),
			new DccInfoNormalizer(),
			new AmountNormalizer(),
			new CardNormaliser(),
			new CvnNormaliser(),
			new CvnNumberNormaliser(),
			new PayerNormalizer(),
			new PayerAddressNormalizer(),
			new PaymentDataNormalizer(),
			new PhoneNumbersNormalizer(),
			new DccInfoResultNormalizer(),
			new AutoSettleNormalizer(),
			new FraudFilterNormalizer(),
            new FraudFilterRuleCollectionNormalizer(),
            new FraudFilterRuleNormalizer()
			//new ObjectNormalizer()
		);
		self::$marshallers[ MessageType::PAYMENT ] = new Serializer( $normalizers, $encoders );

		$encoders                                         = array(
			new CustomStringXmlEncoder( 'response', array(
				'timestamp',
				'number'
			) )
		);
		$normalizers                                      = array(
			new ThreeDSecureRequestNormalizer(),
			new ThreeDSecureResponseNormalizer(),
			new CommentsNormalizer(),
			new AmountNormalizer(),
			new CardNormaliser(),
			new CvnNormaliser(),
			new PaymentDataNormalizer(),
			new CvnNumberNormaliser(),
			new AutoSettleNormalizer(),
			new FraudFilterNormalizer(),
            new FraudFilterRuleCollectionNormalizer(),
            new FraudFilterRuleNormalizer()


            //new  ObjectNormalizer()
		);
		self::$marshallers[ MessageType::THREE_D_SECURE ] = new Serializer( $normalizers, $encoders );

	}

	private static function getRootName( $object ) {
		if ( $object instanceof iRequest ) {
			return "request";
		} else {
			return "response";
		}
	}

	private static function getClassName( $xml, MessageType $messageType ) {

		switch ( $messageType ) {
			case MessageType::PAYMENT: {
				if ( self::IsRequest( $xml ) ) {
					return PaymentRequest::GetClassName();
				}

				return PaymentResponse::GetClassName();
			}

			case MessageType::THREE_D_SECURE: {
				if ( self::IsRequest( $xml ) ) {
					return ThreeDSecureRequest::GetClassName();

				}

				return ThreeDSecureResponse::GetClassName();
			}

		}
	}

	private static function IsRequest( $xml ) {

		return strpos( $xml, "<request" ) !== false;
	}


}


