<?php


namespace com\realexpayments\remote\sdk\utils;

use DateTime;


/**
 * Utils for the auto-generation of fields, for example the SHA1 hash.
 *
 * @package com\realexpayments\remote\sdk\utils
 * @author vicpada
 */
class GenerationUtils {

	/**
	 * Each message sent to Realex should have a hash attached. For a message using the remote
	 * interface this is generated from the TIMESTAMP, MERCHANT_ID, ORDER_ID, AMOUNT, and CURRENCY
	 * fields concatenated together with "." in between each field. This confirms the message comes
	 * from the client.
	 * Generate a hash, required for all messages sent to Realex to prove it was not tampered with.
	 * <p>
	 * Hashing takes a string as input and produces a fixed size number (160 bits for SHA-1 which
	 * this implementation uses). This number is a hash of the input, and a small change in the
	 * input results in a substantial change in the output. The functions are thought to be secure
	 * in the sense that it requires an enormous amount of computing power and time to find a string
	 * that hashes to the same value. In others words, there's no way to decrypt a secure hash.
	 * Given the larger key size, this implementation uses SHA-1 which we prefer that you use, but Realex
	 * has retained compatibilty with MD5 hashing for compatibility with older systems.
	 * <p>
	 * <p>
	 * To construct the hash for the remote interface follow this procedure:
	 *
	 * Form a string by concatenating the above fields with a period ('.') in the following order
	 * <p>
	 * (TIMESTAMP.MERCHANT_ID.ORDER_ID.AMOUNT.CURRENCY)
	 * <p>
	 * Like so (where a field is empty an empty string "" is used):
	 * <p>
	 * (20120926112654.thestore.ORD453-11.29900.EUR)
	 * <p>
	 * Get the hash of this string (SHA-1 shown below).
	 * <p>
	 * (b3d51ca21db725f9c7f13f8aca9e0e2ec2f32502)
	 * <p>
	 * Create a new string by concatenating this string and your shared secret using a period.
	 * <p>
	 * (b3d51ca21db725f9c7f13f8aca9e0e2ec2f32502.mysecret )
	 * <p>
	 * Get the hash of this value. This is the value that you send to Realex Payments.
	 * <p>
	 * (3c3cac74f2b783598b99af6e43246529346d95d1)
	 *
	 * This method takes the pre-built string of concatenated fields and the secret and returns the
	 * SHA-1 hash to be placed in the request sent to Realex.
	 *
	 * @param string $toHash
	 * @param string $secret
	 *
	 * @return string The hash as a hex string
	 */
	public static function generateHash( $toHash, $secret ) {

		//first pass hashes the String of required fields
		$toHashFirstPass = sha1( $toHash );

		//second pass takes the first hash, adds the secret and hashes again
		$toHashSecondPass = $toHashFirstPass . "." . $secret;

		return sha1( $toHashSecondPass );

	}

	/**
	 * Generate the current datetimestamp in the string formaat (YYYYMMDDHHSS) required in a
	 * request to Realex.
	 *
	 * @return string current timestamp in YYYYMMDDHHSS format
	 */
	public static function generateTimestamp() {

		$date = new DateTime();

		return $date->format( "YmdHis" );

	}

	/**
	 * Order Id for a initial request should be unique per client ID. This method generates a unique
	 * order Id using the PHP GUID function and then converts it to base64 to shorten the length to 22
	 * characters. Order Id for a subsequent request (void, rebate, settle etc.) should use the
	 * order Id of the initial request.
	 *
	 * * the order ID uses the PHP GUID (globally unique identifier) so in theory it may not
	 * be unique but the odds of this are extremely remote (see
	 * <a href="https://en.wikipedia.org/wiki/Globally_unique_identifier">
	 * https://en.wikipedia.org/wiki/Globally_unique_identifier</a>)
	 *
	 */
	public static function generateOrderId() {

		$uuid                 = self::getGuid();
		$mostSignificantBits  = substr( $uuid, 0, 8 );
		$leastSignificantBits = substr( $uuid, 23, 8 );


		return substr( base64_encode( $mostSignificantBits . $leastSignificantBits ), 0, 22 );
	}


	private static function getGuid() {

		self::pauseExecution();

		if ( function_exists( 'com_create_guid' ) ) {
			return trim( com_create_guid(), '{}' );
		} else {

			mt_srand( (double) microtime() * 10000 );//optional for php 4.2.0 and up.
			$charId = strtoupper( md5( uniqid( rand(), true ) ) );
			$hyphen = chr( 45 );// "-"
			$uuid   = chr( 123 )// "{"
			          . substr( $charId, 0, 8 ) . $hyphen
			          . substr( $charId, 8, 4 ) . $hyphen
			          . substr( $charId, 12, 4 ) . $hyphen
			          . substr( $charId, 16, 4 ) . $hyphen
			          . substr( $charId, 20, 12 )
			          . chr( 125 );// "}"
			return $uuid;
		}

	}

	private static function pauseExecution() {
		// pause the execution for 100 milliseconds to avoid name collission
		// in case many ids are generated in one go
		usleep( 100000 );
	}

}