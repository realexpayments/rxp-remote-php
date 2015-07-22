<?php


namespace com\realexpayments\remote\sdk\utils;

use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\RealexException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\OXM\Configuration;
use Doctrine\OXM\Mapping\ClassMetadataFactory;
use Doctrine\OXM\Marshaller\XmlMarshaller;
use Exception;
use Logger;


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
	 * @var XmlMarshaller marshaller
	 */
	private static $marshaller;


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

			$xml = self::$marshaller->marshalToString($object);

		} catch ( Exception $e ) {
			self::$logger->error( "Error unmarshalling to XML", $e );
			throw new RealexException( "Error unmarshalling to XML", $e );
		}

		return $xml;
	}


	/**
	 * @param string $xml
	 * @param MessageType $messageType
	 *
	 * @return object
	 */
	public static function  fromXml( $xml, MessageType $messageType ) {
		self::Initialise();

		self::$logger->debug( "Unmarshalling XML to domain object" );
		$object = null;

		try {

			$object = self::$marshaller->unmarshalFromString($xml);

		} catch ( Exception $e ) {
			self::$logger->error( "Error unmarshalling from XML", $e );
			throw new RealexException( "Error unmarshalling from XML", $e );
		}

		return $object;
	}

	private static function Initialise() {
		if ( self::$initialised ) {
			return;
		}

		self::$logger = Logger::getLogger( self::class );

		self::InitialiseMarshaller();

		self::$initialised = true;
	}

	private static function InitialiseMarshaller() {

		$config = new Configuration();
		$config->setMetadataDriverImpl( $config->newDefaultAnnotationDriver( array(
			"src/main/php/com-realexpayments-remote-sdk/domain/payment/PaymentRequest.php",
			"src/main/php/com-realexpayments-remote-sdk/domain/payment/PaymentResponse.php"
		) ) );

		$config->setMetadataCacheImpl(new ArrayCache());
		$metadataFactory = new ClassMetadataFactory($config);
		self::$marshaller = new XmlMarshaller($metadataFactory);

	}


}
