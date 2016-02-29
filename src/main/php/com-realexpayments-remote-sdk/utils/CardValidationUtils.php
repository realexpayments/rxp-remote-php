<?php


namespace com\realexpayments\remote\sdk\utils;

use com\realexpayments\remote\sdk\domain\CardType;


/**
 * Class CardValidationUtils
 *
 * @package com\realexpayments\remote\sdk\utils
 * @author vicpada
 */
class CardValidationUtils {

	/**
	 * Method to perform a Luhn check on the card number.  This allows the SDK user to perform
	 * basic validation on the card number. Any whitespaces or '-' should be stripped out
	 * before validation.
	 *
	 * @param string $cardNumber
	 *
	 * @return bool
	 */
	public static function performLuhnCheck( $cardNumber ) {

		if ( is_null( $cardNumber ) || $cardNumber == "" ) {
			return false;
		}

		/** If string has alpha characters it is not a valid credit card **/
		if ( preg_match( '/^[0-9]*$/', $cardNumber ) == 0 ) {
			return false;
		}

		/** Check length of credit card is valid (between 12 and 19 digits) **/
		$length = strlen( $cardNumber );
		if ( $length < 12 || $length > 19 ) {
			return false;
		}

		/** Perform luhn check **/
		$sum      = 0;
		$digit    = 0;
		$addend   = 0;
		$timesTwo = false;

		for ( $i = $length - 1; $i >= 0; $i -- ) {
			$digit = intval( substr( $cardNumber, $i, 1 ) );
			if ( $timesTwo ) {
				$addend = $digit * 2;
				if ( $addend > 9 ) {
					$addend -= 9;
				}
			} else {
				$addend = $digit;
			}
			$sum += $addend;
			$timesTwo = ! $timesTwo;
		}

		$modulus = $sum % 10;

		return $modulus == 0;
	}


	/**
	 * Method to perform a CVV number check.  The CVV must be 4 digits for AMEX and 3 digits for
	 * all other cards.
	 *
	 * @param string $cvvNumber
	 * @param string $cardType
	 *
	 * @return bool
	 */
	public static function performCvvCheck( $cvvNumber, $cardType ) {

		/* If string has alpha characters it is not a CVV number */
		if ( preg_match( "/^\\d+$/", $cvvNumber ) == 0 ) {
			return false;
		}

		/* Length should be four digits long for AMEX */
		if ( strtolower( $cardType ) == strtolower( CardType::AMEX ) ) {
			if ( strlen( $cvvNumber ) != 4 ) {
				return false;
			}
		} /* Otherwise the length should be three digits */
		elseif ( strlen( $cvvNumber ) != 3 ) {
			return false;
		}

		return true;
	}

	/**
	 * Method to perform an expiry date check.  This allows the SDK user to perform basic validation
	 * on the card number. Should be two digits for the month followed by two digits for the year and
	 * may not be in the past. Any whitespaces or '-' should be stripped out before validation.
	 *
	 * @param string $expiryDate
	 *
	 * @return bool
	 */
	public static function performExpiryDateCheck( $expiryDate ) {

		/* Length should be four digits long */
		if ( strlen( $expiryDate ) != 4 ) {
			return false;
		}

		$mm = substr( $expiryDate, 0, 2 );
		$yy = substr( $expiryDate, 2, 2 );

		if ( ! is_numeric( $mm ) || ! is_numeric( $yy ) ) {
			return false;
		}

		$month = intval( $mm );
		$year  = intval( $yy );


		// Month range is 1-12
		if ( $month < 1 || $month > 12 ) {
			return false;
		}

		// Date is not in the past
		$currentYear  = intval( date( "y" ) );
		$currentMonth = intval( date( "m" ) );


		if ( $year < ( $currentYear % 100 ) ) {
			return false;
		} elseif ( $year == ( $currentYear % 100 ) && $month < $currentMonth ) {
			return false;
		}

		return true;
	}
}