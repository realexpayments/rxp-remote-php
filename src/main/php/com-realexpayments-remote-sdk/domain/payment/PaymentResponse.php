<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\DccInfoResult;
use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\utils\GenerationUtils;
use com\realexpayments\remote\sdk\utils\MessageType;
use com\realexpayments\remote\sdk\utils\ResponseUtils;
use com\realexpayments\remote\sdk\utils\XmlUtils;


/**
 * <p>
 * Class representing a Payment response received from Realex.
 * </p>
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 */
class PaymentResponse implements iResponse {

	/**
	 * @var string Time stamp in the format YYYYMMDDHHMMSS, which represents the time in the format year
	 * month date hour minute second.
	 *
	 */
	private $timeStamp;

	/**
	 * @var string Represents Realex Payments assigned merchant id.
	 *
	 */
	private $merchantId;

	/**
	 * @var string Represents the Realex Payments subaccount to use. If you omit this element then
	 * we will use your default account.
	 *
	 */
	private $account;

	/**
	 * @var string Represents the unique order id of this transaction. Must be unique across all of your accounts.
	 *
	 */
	private $orderId;

	/**
	 * @var string The result codes returned by the Realex Payments system.
	 *
	 */
	private $result;

	/**
	 * @var string If successful an authcode is returned from the bank. Used when referencing
	 * this transaction in refund and void requests.
	 *
	 */
	private $authCode;

	/**
	 * @var string The text of the response.
	 *
	 */
	private $message;

	/**
	 * @var string The Realex payments reference (pasref) for the transaction. Used when referencing
	 * this transaction in refund and void requests.
	 *
	 */
	private $paymentsReference;

	/**
	 * <p>
	 * The result of the Card Verification check.
	 * </p>
	 * <p>
	 * <ul>
	 * <li>M: CVV Matched</li>
	 * <li>N: CVV Not Matched</li>
	 * <li>I: CVV Not checked due to circumstances</li>
	 * <li>U: CVV Not checked - issuer not certified</li>
	 * <li>P: CVV Not Processed</li>
	 * </ul>
	 * </p>
	 *
	 * @var string
	 *
	 */
	private $cvnResult;

	/**
	 * @var int The time taken
	 *
	 */
	private $timeTaken;

	/**
	 * @var int The AUTH time taken.
	 *
	 */
	private $authTimeTaken;

	/**
	 * @var string The raw XML response from the acquirer (if the account is set up to return this).
	 *
	 */
	private $acquirerResponse;

	/**
	 * @var int The batch id of the transaction. Returned in the case of auth and refund requests.
	 * This can be used to assist with the reconciliation of your batches.
	 *
	 */
	private $batchId;

	/**
	 * @var CardIssuer The details of the cardholder's bank (if available).
	 *
	 */
	private $cardIssuer;

	/**
	 * @var string The SHA-1 hash of certain elements of the response. The details of this are to be found
	 * in the realauth developer's guide.
	 *
	 */
	private $hash;

	/**
	 * @var TssResult The results of realscore.
	 *
	 */
	private $tssResult;

	/**
	 * <p>
	 * Contains postcode match result from Address Verification Service.
	 * </p>
	 * <ul>
	 * <li>M: Matched</li>
	 * <li>N: Not Matched</li>
	 * <li>I: Problem with check</li>
	 * <li>U: Unable to check</li>
	 * <li>P: Partial match</li>
	 * </ul>
	 *
	 * @var string
	 *
	 */
	private $avsPostcodeResponse;

	/**
	 * <p>
	 * Contains address match result from Address Verification Service.
	 * </p>
	 * <ul>
	 * <li>M: Matched</li>
	 * <li>N: Not Matched</li>
	 * <li>I: Problem with check</li>
	 * <li>U: Unable to check</li>
	 * <li>P: Partial match</li>
	 * </ul>
	 *
	 * @var string
	 *
	 */
	private $avsAddressResponse;

	/**
	 * @var DccInfoResult The results of dcc rate lookup
	 */
	private $dccInfoResult;


	/**
	 * @var FraudFilter
	 *
	 */
	private $fraudFilter;

	/**
	 * PaymentResponse constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}


	/**
	 * <p>
	 * Method returns a concrete implementation of the response class from an XML source.
	 * </p>
	 *
	 * @param string $xml
	 *
	 * @return iResponse
	 */
	public function fromXML( $xml ) {
		return XmlUtils::fromXml( $xml, new MessageType( MessageType::PAYMENT ) );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string
	 */
	public function toXML() {
		return XmlUtils::toXml( $this, new MessageType( MessageType::PAYMENT ) );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param string $secret
	 *
	 * @return bool
	 */
	public function isHashValid( $secret ) {

		$hashValid = false;

		//check for any null values and set them to empty $for hashing
		$timeStamp         = null == $this->timeStamp ? "" : $this->timeStamp;
		$merchantId        = null == $this->merchantId ? "" : $this->merchantId;
		$orderId           = null == $this->orderId ? "" : $this->orderId;
		$result            = null == $this->result ? "" : $this->result;
		$message           = null == $this->message ? "" : $this->message;
		$paymentsReference = null == $this->paymentsReference ? "" : $this->paymentsReference;
		$authCode          = null == $this->authCode ? "" : $this->authCode;

		//create $to hash
		$toHash = $timeStamp
		          . "."
		          . $merchantId
		          . "."
		          . $orderId
		          . "."
		          . $result
		          . "."
		          . $message
		          . "."
		          . $paymentsReference
		          . "."
		          . $authCode;

		//check if calculated hash matches returned value
		$expectedHash = GenerationUtils::generateHash( $toHash, $secret );
		if ( $expectedHash == $this->hash ) {
			$hashValid = true;
		}

		return $hashValid;
	}

	/**
	 * Returns the result from the response.
	 *
	 * @return string
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * Returns the message from the response.
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Returns the orderId from the response.
	 *
	 * @return string
	 */
	public function  getOrderId() {
		return $this->orderId;
	}

	/**
	 * Returns the timestamp from the response.
	 *
	 * @return string
	 */
	public function getTimeStamp() {
		return $this->timeStamp;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return bool
	 */
	public function isSuccess() {
		return ResponseUtils::isSuccess( $this );
	}

	/**
	 * Setter for timeStamp
	 *
	 * @param string $timeStamp
	 */
	public function setTimeStamp( $timeStamp ) {
		$this->timeStamp = $timeStamp;
	}

	/**
	 * Getter for merchantId
	 *
	 * @return string
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Setter for merchantId
	 *
	 * @param string $merchantId
	 */
	public function setMerchantId( $merchantId ) {
		$this->merchantId = $merchantId;
	}

	/**
	 * Getter for account
	 *
	 * @return string
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * Setter for account
	 *
	 * @param string $account
	 */
	public function setAccount( $account ) {
		$this->account = $account;
	}

	/**
	 * Setter for orderId
	 *
	 * @param string $orderId
	 */
	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	/**
	 * Setter for result
	 *
	 * @param string $result
	 */
	public function setResult( $result ) {
		$this->result = $result;
	}

	/**
	 * Getter for authCode
	 *
	 * @return string
	 */
	public function getAuthCode() {
		return $this->authCode;
	}

	/**
	 * Setter for authCode
	 *
	 * @param string $authCode
	 */
	public function setAuthCode( $authCode ) {
		$this->authCode = $authCode;
	}

	/**
	 * Setter for message
	 *
	 * @param string $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * Getter for paymentsReference
	 *
	 * @return string
	 */
	public function getPaymentsReference() {
		return $this->paymentsReference;
	}

	/**
	 * Setter for paymentsReference
	 *
	 * @param string $paymentsReference
	 */
	public function setPaymentsReference( $paymentsReference ) {
		$this->paymentsReference = $paymentsReference;
	}

	/**
	 * Getter for cvnResult
	 *
	 * @return string
	 */
	public function getCvnResult() {
		return $this->cvnResult;
	}

	/**
	 * Setter for cvnResult
	 *
	 * @param string $cvnResult
	 */
	public function setCvnResult( $cvnResult ) {
		$this->cvnResult = $cvnResult;
	}

	/**
	 * Getter for timeTaken
	 *
	 * @return int
	 */
	public function getTimeTaken() {
		return $this->timeTaken;
	}

	/**
	 * Setter for timeTaken
	 *
	 * @param int $timeTaken
	 */
	public function setTimeTaken( $timeTaken ) {
		$this->timeTaken = $timeTaken;
	}

	/**
	 * Getter for authTimeTaken
	 *
	 * @return int
	 */
	public function getAuthTimeTaken() {
		return $this->authTimeTaken;
	}

	/**
	 * Setter for authTimeTaken
	 *
	 * @param int $authTimeTaken
	 */
	public function setAuthTimeTaken( $authTimeTaken ) {
		$this->authTimeTaken = $authTimeTaken;
	}

	/**
	 * Getter for acquirerResponse
	 *
	 * @return string
	 */
	public function getAcquirerResponse() {
		return $this->acquirerResponse;
	}

	/**
	 * Setter for acquirerResponse
	 *
	 * @param string $acquirerResponse
	 */
	public function setAcquirerResponse( $acquirerResponse ) {
		$this->acquirerResponse = $acquirerResponse;
	}

	/**
	 * Getter for batchId
	 *
	 * @return int
	 */
	public function getBatchId() {
		return $this->batchId;
	}

	/**
	 * Setter for batchId
	 *
	 * @param int $batchId
	 */
	public function setBatchId( $batchId ) {
		$this->batchId = $batchId;
	}

	/**
	 * Getter for cardIssuer
	 *
	 * @return CardIssuer
	 */
	public function getCardIssuer() {
		return $this->cardIssuer;
	}

	/**
	 * Setter for cardIssuer
	 *
	 * @param CardIssuer $cardIssuer
	 */
	public function setCardIssuer( $cardIssuer ) {
		$this->cardIssuer = $cardIssuer;
	}

	/**
	 * Getter for hash
	 *
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Setter for hash
	 *
	 * @param string $hash
	 */
	public function setHash( $hash ) {
		$this->hash = $hash;
	}

	/**
	 * Getter for tssResult
	 *
	 * @return TssResult
	 */
	public function getTssResult() {
		return $this->tssResult;
	}

	/**
	 * Setter for tssResult
	 *
	 * @param TssResult $tssResult
	 */
	public function setTssResult( $tssResult ) {
		$this->tssResult = $tssResult;
	}

	/**
	 * Getter for avsPostcodeResponse
	 *
	 * @return string
	 */
	public function getAvsPostcodeResponse() {
		return $this->avsPostcodeResponse;
	}

	/**
	 * Setter for avsPostcodeResponse
	 *
	 * @param string $avsPostcodeResponse
	 */
	public function setAvsPostcodeResponse( $avsPostcodeResponse ) {
		$this->avsPostcodeResponse = $avsPostcodeResponse;
	}

	/**
	 * Getter for avsAddressResponse
	 *
	 * @return string
	 */
	public function getAvsAddressResponse() {
		return $this->avsAddressResponse;
	}

	/**
	 * Setter for avsAddressResponse
	 *
	 * @param string $avsAddressResponse
	 */
	public function setAvsAddressResponse( $avsAddressResponse ) {
		$this->avsAddressResponse = $avsAddressResponse;
	}

	/**
	 * Getter for dccInfoResult
	 *
	 * @return DccInfoResult
	 */
	public function getDccInfoResult() {
		return $this->dccInfoResult;
	}

	/**
	 * Setter for dccInfoResult
	 *
	 * @param DccInfoResult $dccInfoResult
	 */
	public function setDccInfoResult( $dccInfoResult ) {
		$this->dccInfoResult = $dccInfoResult;
	}

	/**
	 * Getter for fraudFilter
	 *
	 * @return fraudFilter
	 */
	public function getFraudFilter() {
		return $this->fraudFilter;
	}

	/**
	 * Setter for fraudFilter
	 *
	 * @param FraudFilter $fraudFilter
	 */
	public function setFraudFilter( $fraudFilter ) {
		$this->fraudFilter = $fraudFilter;
	}


}