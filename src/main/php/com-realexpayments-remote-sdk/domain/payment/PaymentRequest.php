<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\iRequest;
use com\realexpayments\remote\sdk\utils\GenerationUtils;
use com\realexpayments\remote\sdk\utils\MessageType;
use com\realexpayments\remote\sdk\utils\XmlUtils;
use Doctrine\OXM\Mapping as DOM;


/**
 * Class PaymentRequest
 *
 ** <p>
 * Class representing a Payment request to be sent to Realex.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example AUTH:
 * </p>
 * <p><code><pre>
 * $card = (new Card())
 *    ->addExpiryDate("0119")
 *    ->addNumber("420000000000000000")
 *    ->addType(CardType.VISA)
 *    ->addCardHolderName("Joe Smith")
 *    ->addCvn(123)
 *    ->addCvnPresenceIndicator(PresenceIndicator.CVN_PRESENT);
 *
 * $request = (new PaymentRequest())
 *    ->addAccount("yourAccount")
 *    ->addMerchantId("yourMerchantId")
 *    ->addType(PaymentType.AUTH)
 *    ->addAmount(100)
 *    ->addCurrency("EUR")
 *    ->addCard(card)
 *    ->addAutoSettle(new AutoSettle()->addFlag(AutoSettleFlag.TRUE));
 * </pre></code></p>
 *
 * <p>
 * Example AUTH with Address Verification:
 * <p>
 * <p><code><pre>
 * $card = (new Card())
 *    ->addExpiryDate("0119")
 *    ->addNumber("420000000000000000")
 *    ->addType(CardType.VISA)
 *    ->addCardHolderName("Joe Smith")
 *    ->addCvn(123)
 *    ->addCvnPresenceIndicator(PresenceIndicator.CVN_PRESENT);
 *
 * $request = (new PaymentRequest())
 *    ->addAccount("yourAccount")
 *    ->addMerchantId("yourMerchantId")
 *    ->addType(PaymentType.AUTH)
 *    ->addAmount(100)
 *    ->addCurrency("EUR")
 *    ->addCard(card)
 *    ->addAutoSettle(new AutoSettle().addFlag(AutoSettleFlag.TRUE))
 *    ->addAddressVerificationServiceDetails("382 The Road", "WB1 A42");
 * </pre></code></p>
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\payment
 * @Dom\XmlRootEntity(xml="request")
 */
class PaymentRequest implements iRequest {


	/**
	 * @var string Format of timestamp is yyyyMMddhhmmss  e.g. 20150131094559 for 31/01/2015 09:45:59.
	 * If the timestamp is more than a day (86400 seconds) away from the server time, then the request is rejected.
	 *
	 * @Dom\XmlAttribute(type="string",name="timestamp")
	 */
	private $timestamp;

	/**
	 * @var string The payment type
	 *
	 * @Dom\XmlAttribute(type="string",name="type")
	 */
	private $type;

	/**
	 * @var string Represents Realex Payments assigned merchant id.
	 *
	 * @Dom\XmlText(type="string",name="merchantid")
	 */
	private $merchantId;

	/**
	 * @var string Represents the Realex Payments subaccount to use. If this element is omitted, then the
	 * default account is used.
	 *
	 * @Dom\XmlText(type="string",name="account")
	 */
	private $account;

	/**
	 * @var string For certain acquirers it is possible to specify whether a transaction is to be processed as a
	 * Mail Order/Telephone Order or Ecommerce transaction. For other banks, this is configured on the
	 * Merchant ID level.
	 *
	 * @Dom\XmlText(type="string",name="channel")
	 */
	private $channel;

	/**
	 * @var string Represents the unique order id of this transaction. Must be unique across all of the sub-accounts.
	 *
	 * @Dom\XmlText(type="string",name="orderid")
	 */
	private $orderId;

	/**
	 * @var Amount Object containing the amount value and the currency type.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\Amount",name="amount")
	 */
	private $amount;

	/**
	 * @var Card Object containing the card details to be passed in request.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\Card", name="card")
	 */
	private $card;

	/**
	 * @var AutoSettle Object containing the auto settle flag.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\AutoSettle",name="autoSettle")
	 */
	private $autoSettle;

	/**
	 * @var string Hash constructed from the time stamp, merchand ID, order ID, amount, currency, card number
	 * and secret values.
	 *
	 * @Dom\XmlText(type="string", name="sha1hash")
	 */
	private $hash;

	/**
	 * @var CommentCollection List of {@link Comment} objects to be passed in request. Optionally, up to two comments
	 * can be associated with any transaction.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\CommentCollection", name="comments")
	 */
	private $comments;

	/**
	 * <p>
	 * Represents the Realex Payments reference of the original transaction (this is included in the
	 * response to the auth).
	 * </p>
	 * <p>
	 * Used in requests where the original transaction must be referenced e.g. a REBATE request.
	 * </p>
	 *
	 * @var string payment reference
	 *
	 * @Dom\XmlText(type="string",name="pasref")
	 */
	private $paymentsReference;

	/**
	 * <p>
	 * Represents the authcode of the original transaction, which was included in the response.
	 * </p>
	 * <p>
	 * Used in requests where the original transaction must be referenced e.g. a REBATE request.
	 * </p>
	 *
	 * @var string auth code
	 *
	 * @Dom\XmlText(type="string",name="authcode")
	 */
	private $authCode;

	/**
	 * @var string Represents a hash of the refund password, which Realex Payments will provide. The SHA1
	 * algorithm must be used to generate this hash.
	 *
	 * @Dom\XmlText(type="string",name="refundhash")
	 */
	private $refundHash;

	/**
	 * @var string wellTODO - info on this
	 *
	 * @Dom\XmlText(type="string",name="fraudfilter")
	 */
	private $fraudFilter;

	/**
	 * @var Recurring If you are configured for recurring/continuous authority transactions, you must set the recurring values.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\Recurring", name="recurring")
	 */
	private $recurring;

	/**
	 * @var TssInfo Contains optional variables which can be used to identify customers in the
	 * Realex Payments system.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\TssInfo", name="tssinfo")
	 */
	private $tssInfo;

	/**
	 * @var Mpi Contains 3D Secure/Secure Code information if this transaction has used a 3D
	 * Secure/Secure Code system, either Realex's RealMPI or a third party's.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\Mpi", name="mpi")
	 */
	private $mpi;

	/**
	 * Constructor for Payment Request
	 */
	public function __construct() {

	}


	/**
	 * Getter for TSS info
	 *
	 * @return TssInfo
	 */
	public function getTssInfo() {
		return $this->tssInfo;
	}

	/**
	 * Setter for TSS info
	 *
	 * @param TssInfo $tssInfo
	 */
	public function setTssInfo( TssInfo $tssInfo ) {
		$this->tssInfo = $tssInfo;
	}


	/**
	 * Getter for timestamp
	 *
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * Setter for timestamp
	 *
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * Getter for type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Setter for type
	 *
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
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
	 * Getter for channel
	 *
	 * @return string
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * Setter for channel
	 *
	 * @param string $channel
	 */
	public function setChannel( $channel ) {
		$this->channel = $channel;
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
	 * Getter for amount
	 *
	 * @return Amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Setter for amount
	 *
	 * @param Amount $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	/**
	 * Getter for card
	 *
	 * @return Card
	 */
	public function getCard() {
		return $this->card;
	}

	/**
	 * Setter for card
	 *
	 * @param Card $card
	 */
	public function setCard( $card ) {
		$this->card = $card;
	}

	/**
	 * Getter for autoSettle
	 *
	 * @return AutoSettle
	 */
	public function getAutoSettle() {
		return $this->autoSettle;
	}

	/**
	 * Setter for autoSettle
	 *
	 * @param AutoSettle $autoSettle
	 */
	public function setAutoSettle( $autoSettle ) {
		$this->autoSettle = $autoSettle;
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
	 * Getter for comments
	 *
	 * @return CommentCollection
	 *
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Setter for comments
	 *
	 * @param CommentCollection $comments
	 */
	public function setComments( $comments ) {
		$this->comments = $comments;
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
	 * Getter for refundHash
	 *
	 * @return string
	 */
	public function getRefundHash() {
		return $this->refundHash;
	}

	/**
	 * Setter for refundHash
	 *
	 * @param string $refundHash
	 */
	public function setRefundHash( $refundHash ) {
		$this->refundHash = $refundHash;
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
	 * Getter for fraudFilter
	 *
	 * @return string
	 */
	public function getFraudFilter() {
		return $this->fraudFilter;
	}

	/**
	 * Setter for fraudFilter
	 *
	 * @param string $fraudFilter
	 */
	public function setFraudFilter( $fraudFilter ) {
		$this->fraudFilter = $fraudFilter;
	}

	/**
	 * Getter for recurring
	 *
	 * @return Recurring
	 */
	public function getRecurring() {
		return $this->recurring;
	}

	/**
	 * Setter for recurring
	 *
	 * @param Recurring $recurring
	 */
	public function setRecurring( $recurring ) {
		$this->recurring = $recurring;
	}

	/**
	 * Getter for mpi
	 *
	 * @return Mpi
	 */
	public function getMpi() {
		return $this->mpi;
	}

	/**
	 * Setter for mpi
	 *
	 * @param Mpi $mpi
	 */
	public function setMpi( $mpi ) {
		$this->mpi = $mpi;
	}


	/**
	 * Helper method for adding TSS info
	 *
	 * @param TssInfo $tssInfo
	 *
	 * @return $this
	 */
	public function addTssInfo( TssInfo $tssInfo ) {
		$this->tssInfo = $tssInfo;

		return $this;
	}

	/**
	 * Helper method for adding a type
	 *
	 * @param string $type
	 *
	 * @return PaymentRequest
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method for adding a type
	 *
	 * @param PaymentType $type
	 *
	 * @return PaymentRequest
	 */
	public function addPaymentType( PaymentType $type ) {
		$this->type = $type->getType();

		return $this;
	}

	/**
	 * Helper method for adding a merchantId
	 *
	 * @param string $merchantId
	 *
	 * @return PaymentRequest
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
	 * @return PaymentRequest
	 */
	public function addAccount( $account ) {
		$this->account = $account;

		return $this;
	}

	/**
	 * Helper method for adding a timestamp
	 *
	 * @param string $timestamp
	 *
	 * @return PaymentRequest
	 */
	public function addTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;

		return $this;
	}

	/**
	 * Helper method for adding a orderId
	 *
	 * @param string $orderId
	 *
	 * @return PaymentRequest
	 */
	public function addOrderId( $orderId ) {
		$this->orderId = $orderId;

		return $this;
	}

	/**
	 * Helper method for adding a amount
	 *
	 * @param int $amount
	 *
	 * @return PaymentRequest
	 */
	public function addAmount( $amount ) {
		if ( is_null( $this->amount ) ) {
			$this->amount = new Amount();
			$this->amount->addAmount( $amount );
		} else {
			$this->amount->addAmount( $amount );
		}

		return $this;
	}

	/**
	 * Helper method for adding a currency
	 *
	 * @param string $currency
	 *
	 * @return PaymentRequest
	 */
	public function addCurrency( $currency ) {
		if ( is_null( $this->amount ) ) {
			$this->amount = new Amount();
			$this->amount->addCurrency( $currency );
		} else {
			$this->amount->addCurrency( $currency );
		}

		return $this;
	}

	/**
	 * Helper method for adding a card
	 *
	 * @param Card $card
	 *
	 * @return PaymentRequest
	 */
	public function addCard( Card $card ) {
		$this->card = $card;

		return $this;
	}

	/**
	 * Helper method for adding a autoSettle
	 *
	 * @param AutoSettle $autoSettle
	 *
	 * @return PaymentRequest
	 */
	public function addAutoSettle( AutoSettle $autoSettle ) {
		$this->autoSettle = $autoSettle;

		return $this;
	}

	/**
	 * Helper method for adding a comment. NB Only 2 comments will be accepted by Realex.
	 *
	 * @param string $comment
	 *
	 * @return PaymentRequest
	 */
	public function addComment( $comment ) {
		//create new comments array list if null
		if ( is_null( $this->comments ) ) {
			$this->comments = new CommentCollection();
		}

		$size          = $this->comments->getSize();
		$commentObject = new Comment();
		$this->comments->add( $commentObject->addComment( $comment )->addId( ++ $size ) );

		return $this;
	}

	/**
	 * Helper method for adding a paymentsReference
	 *
	 * @param string $paymentsReference
	 *
	 * @return PaymentRequest
	 */
	public function addPaymentsReference( $paymentsReference ) {
		$this->paymentsReference = $paymentsReference;

		return $this;
	}

	/**
	 * Helper method for adding a authCode
	 *
	 * @param string $authCode
	 *
	 * @return PaymentRequest
	 */
	public function addAuthCode( $authCode ) {
		$this->authCode = $authCode;

		return $this;
	}

	/**
	 * Helper method for adding a refundHash
	 *
	 * @param string $refundHash
	 *
	 * @return PaymentRequest
	 */
	public function addRefundHash( $refundHash ) {
		$this->refundHash = $refundHash;

		return $this;
	}

	/**
	 * Helper method for adding a hash
	 *
	 * @param string $hash
	 *
	 * @return PaymentRequest
	 */
	public function addHash( $hash ) {
		$this->hash = $hash;

		return $this;
	}

	/**
	 * Helper method for adding a channel
	 *
	 * @param string $channel
	 *
	 * @return PaymentRequest
	 */
	public function addChannel( $channel ) {
		$this->channel = $channel;

		return $this;
	}

	/**
	 * Helper method for adding a fraudFilter
	 *
	 * @param string $fraudFilter
	 *
	 * @return PaymentRequest
	 */
	public function addFraudFilter( $fraudFilter ) {
		$this->fraudFilter = $fraudFilter;

		return $this;
	}

	/**
	 * Helper method for adding a recurring
	 *
	 * @param Recurring $recurring
	 *
	 * @return PaymentRequest
	 */
	public function addRecurring( Recurring $recurring ) {
		$this->recurring = $recurring;

		return $this;
	}

	/**
	 * Helper method for adding a mpi
	 *
	 * @param Mpi $mpi
	 *
	 * @return PaymentRequest
	 */
	public function addMpi( Mpi $mpi ) {
		$this->mpi = $mpi;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toXml() {
		return XmlUtils::toXml( $this, new MessageType( MessageType::PAYMENT ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function fromXml( $xml ) {
		return XmlUtils::fromXml( $xml, new MessageType( MessageType::PAYMENT ) );
	}


	/**
	 * {@inheritdoc}
	 */
	public function generateDefaults( $secret ) {

		//generate timestamp if not set
		if ( is_null( $this->timestamp ) ) {
			$this->timestamp = GenerationUtils::generateTimestamp();
		}

		//generate order ID if not set
		if ( is_null( $this->orderId ) ) {
			$this->orderId = GenerationUtils::generateOrderId();
		}

		//generate hash
		$this->hash( $secret );

		return $this;


	}

	/**
	 * {@inheritdoc}
	 */
	public function responseFromXml( $xml ) {

		$paymentResponse = new PaymentResponse();

		return $paymentResponse->fromXML( $xml );
	}

	/**
	 * Creates the security hash from a number of fields and the shared secret.
	 *
	 * @param string $secret
	 *
	 * @return PaymentRequest
	 */
	public function hash( $secret ) {

		//check for any null values and set them to empty string for hashing
		$timeStamp  = null == $this->timestamp ? "" : $this->timestamp;
		$merchantId = null == $this->merchantId ? "" : $this->merchantId;
		$orderId    = null == $this->orderId ? "" : $this->orderId;
		$amount     = "";
		$currency   = "";

		if ( $this->amount != null ) {
			$amount   = null == $this->amount->getAmount() ? "" : $this->amount->getAmount();
			$currency = null == $this->amount->getCurrency() ? "" : $this->amount->getCurrency();
		}

		$cardNumber = "";

		if ( $this->card != null ) {
			$cardNumber = null == $this->card->getNumber() ? "" : $this->card->getNumber();
		}

		//create String to hash
		$toHash = $timeStamp
		          . "."
		          . $merchantId
		          . "."
		          . $orderId
		          . "."
		          . $amount
		          . "."
		          . $currency
		          . "."
		          . $cardNumber;

		$this->hash = GenerationUtils::generateHash( $toHash, $secret );

		return $this;

	}

	/**
	 * <p>
	 * This helper method adds Address Verification Service (AVS) fields to the request.
	 * </p>
	 * <p>
	 * The Address Verification Service (AVS) verifies the cardholder's address by checking the
	 * information provided by at the time of sale against the issuing bank's records.
	 * </p>
	 *
	 * @param string $addressLine
	 * @param string $postcode
	 *
	 * @return PaymentRequest
	 */
	public function addAddressVerificationServiceDetails( $addressLine, $postcode ) {

		//build code in format <digits from postcode>|<digits from address>
		$postcodeDigits    = preg_replace( "/\\D+/", "", $postcode );
		$addressLineDigits = preg_replace( "/\\D+/", "", $addressLine );
		$code              = $postcodeDigits . "|" . $addressLineDigits;
		//construct billing address from code
		$address = new Address();

		$address->addCode( $code )
		        ->addType( AddressType::BILLING );

		//add address to TSS Info
		if ( is_null( $this->tssInfo ) ) {
			$this->tssInfo = new TssInfo();
		}
		$this->tssInfo->addAddress( $address );

		return $this;
	}
}