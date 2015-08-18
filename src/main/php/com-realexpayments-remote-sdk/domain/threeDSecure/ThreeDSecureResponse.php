<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure;


use com\realexpayments\remote\sdk\domain\iResponse;
use com\realexpayments\remote\sdk\utils\GenerationUtils;
use com\realexpayments\remote\sdk\utils\MessageType;
use com\realexpayments\remote\sdk\utils\ResponseUtils;
use com\realexpayments\remote\sdk\utils\XmlUtils;


/**
 * <p>
 * Class representing a 3DSecure response received from Realex.
 * </p>
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\threeDSecure
 */
class ThreeDSecureResponse implements iResponse {

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
	 * @var string The pre-encoded PaReq that you must post to the Issuer's ACS url.
	 *
	 */
	private $pareq;


	/**
	 * @var string The pre-encoded PaReq that you must post to the Issuer's ACS url.
	 *
	 */
	private $url;

	/**
	 * @var string Enrolment response from ACS.
	 *
	 */
	private $enrolled;

	/**
	 * @var string XID from ACS.
	 *
	 */
	private $xid;

	/**
	 * @var ThreeDSecure The 3D Secure details.
	 *
	 */
	private $threeDSecure;


	/**
	 * @var string The SHA-1 hash of certain elements of the response. The details of this are to be found
	 * in the realauth developer's guide.
	 *
	 */
	private $hash;

	/**
	 * ThreeDSecureResponse constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for timeStamp
	 *
	 * @return string
	 */
	public function getTimeStamp() {
		return $this->timeStamp;
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
	 * Getter for orderId
	 *
	 * @return string
	 */
	public function getOrderId() {
		return $this->orderId;
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
	 * Getter for result
	 *
	 * @return string
	 */
	public function getResult() {
		return $this->result;
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
	 * Getter for message
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
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
	 * Getter for pareq
	 *
	 * @return string
	 */
	public function getPareq() {
		return $this->pareq;
	}

	/**
	 * Setter for pareq
	 *
	 * @param string $pareq
	 */
	public function setPareq( $pareq ) {
		$this->pareq = $pareq;
	}

	/**
	 * Getter for url
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Setter for url
	 *
	 * @param string $url
	 */
	public function setUrl( $url ) {
		$this->url = $url;
	}

	/**
	 * Getter for enrolled
	 *
	 * @return string
	 */
	public function getEnrolled() {
		return $this->enrolled;
	}

	/**
	 * Setter for enrolled
	 *
	 * @param string $enrolled
	 */
	public function setEnrolled( $enrolled ) {
		$this->enrolled = $enrolled;
	}

	/**
	 * Getter for xid
	 *
	 * @return string
	 */
	public function getXid() {
		return $this->xid;
	}

	/**
	 * Setter for xid
	 *
	 * @param string $xid
	 */
	public function setXid( $xid ) {
		$this->xid = $xid;
	}

	/**
	 * Getter for threeDSecure
	 *
	 * @return ThreeDSecure
	 */
	public function getThreeDSecure() {
		return $this->threeDSecure;
	}

	/**
	 * Setter for threeDSecure
	 *
	 * @param ThreeDSecure $threeDSecure
	 */
	public function setThreeDSecure( $threeDSecure ) {
		$this->threeDSecure = $threeDSecure;
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
	 * Helper method for adding a timeStamp
	 *
	 * @param string $timeStamp
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addTimeStamp( $timeStamp ) {
		$this->timeStamp = $timeStamp;

		return $this;
	}

	/**
	 * Helper method for adding a merchantId
	 *
	 * @param string $merchantId
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addMerchantId( $merchantId ) {
		$this->merchantId = $merchantId;

		return $this;
	}

	/**
	 * Helper method for adding a account
	 *
	 * @param string $account
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addAccount( $account ) {
		$this->account = $account;

		return $this;
	}

	/**
	 * Helper method for adding a orderId
	 *
	 * @param string $orderId
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addOrderId( $orderId ) {
		$this->orderId = $orderId;

		return $this;
	}

	/**
	 * Helper method for adding a result
	 *
	 * @param string $result
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addResult( $result ) {
		$this->result = $result;

		return $this;
	}

	/**
	 * Helper method for adding a authCode
	 *
	 * @param string $authCode
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addAuthCode( $authCode ) {
		$this->authCode = $authCode;

		return $this;
	}

	/**
	 * Helper method for adding a message
	 *
	 * @param string $message
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addMessage( $message ) {
		$this->message = $message;

		return $this;
	}

	/**
	 * Helper method for adding a paymentsReference
	 *
	 * @param string $paymentsReference
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addPaymentsReference( $paymentsReference ) {
		$this->paymentsReference = $paymentsReference;

		return $this;
	}

	/**
	 * Helper method for adding a timeTaken
	 *
	 * @param int $timeTaken
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addTimeTaken( $timeTaken ) {
		$this->timeTaken = $timeTaken;

		return $this;
	}

	/**
	 * Helper method for adding a authTimeTaken
	 *
	 * @param int $authTimeTaken
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addAuthTimeTaken( $authTimeTaken ) {
		$this->authTimeTaken = $authTimeTaken;

		return $this;
	}

	/**
	 * Helper method for adding a pareq
	 *
	 * @param string $pareq
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addPareq( $pareq ) {
		$this->pareq = $pareq;

		return $this;
	}

	/**
	 * Helper method for adding a url
	 *
	 * @param string $url
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addUrl( $url ) {
		$this->url = $url;

		return $this;
	}

	/**
	 * Helper method for adding a enrolled
	 *
	 * @param string $enrolled
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addEnrolled( $enrolled ) {
		$this->enrolled = $enrolled;

		return $this;
	}

	/**
	 * Helper method for adding a xid
	 *
	 * @param string $xid
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addXid( $xid ) {
		$this->xid = $xid;

		return $this;
	}

	/**
	 * Helper method for adding a threeDSecure
	 *
	 * @param ThreeDSecure $threeDSecure
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addThreeDSecure( $threeDSecure ) {
		$this->threeDSecure = $threeDSecure;

		return $this;
	}

	/**
	 * Helper method for adding a hash
	 *
	 * @param string $hash
	 *
	 * @return ThreeDSecureResponse
	 */
	public function addHash( $hash ) {
		$this->hash = $hash;

		return $this;
	}


	/**
	 * @{@inheritdoc}
	 */
	public function fromXML( $resource ) {
		return XmlUtils::fromXml( $resource, new MessageType( MessageType::THREE_D_SECURE ) );
	}

	/**
	 * @{@inheritdoc}
	 */
	public function toXML() {
		return XmlUtils::toXml( $this, new MessageType( MessageType::THREE_D_SECURE ) );
	}

	/**
	 * @{@inheritdoc}
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
	 * @{@inheritdoc}
	 */
	public function isSuccess() {
		return ResponseUtils::isSuccess( $this );
	}
}