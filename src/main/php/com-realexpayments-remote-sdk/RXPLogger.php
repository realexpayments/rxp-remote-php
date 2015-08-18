<?php


namespace com\realexpayments\remote\sdk;

use Logger;

/**
 * Class RXPLogger. Wraps initialisation of the logging framework
 * @package com\realexpayments\remote\sdk
 */
class RXPLogger {

	/**
	 * @var bool
	 */
	private static $initialised = false;

	/**
	 * @param string $className
	 *
	 * @return Logger
	 */
	public static function GetLogger( $className ) {
		if ( ! self::IsInitialised() ) {
			self::Initialise();
		}

		$logger = Logger::getLogger( $className );

		return $logger;

	}

	private static function Initialise() {

		$path = $_SERVER['DOCUMENT_ROOT'] . '/config.xml';
		if ( file_exists( $path ) ) {
			Logger::configure( $path );
		} else {
			Logger::configure( __DIR__ . '/config.xml' );
		}


		self::$initialised = true;
	}

	private static function IsInitialised() {
		return self::$initialised;
	}

}