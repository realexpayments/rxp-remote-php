<?php


namespace com\realexpayments\remote\sdk\utils;
use Logger;


/**
 * XML helper class. Marshals/unmarshals XML.
 *
 * @author vicpada
 */
class XmlUtils {

	/**
	 * {@link JAXBContext} map used for XML marshalling, unmarshalling.
	 */
	private static $HITCH_CONTEXT_MAP;

	/**
	 * @var Logger logger
	 */
	private static  $logger;

	private static  $initialised = false;


	public static function toXml(\stdClass $stdClass, MessageType $messageType){

		self::Initialise();

		self::$logger->debug("Marshalling domain object to XML");

	}

	private static function Initialise() {
		if (self::$initialised)
			return;

		self::$logger = Logger::getLogger(self::class);

		self::InitialiseHitch();
	}

	private static function InitialiseHitch() {

	}


}