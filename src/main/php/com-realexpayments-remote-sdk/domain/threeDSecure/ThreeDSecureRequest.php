<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\iRequest;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;
use com\realexpayments\remote\sdk\utils\GenerationUtils;
use com\realexpayments\remote\sdk\utils\MessageType;
use com\realexpayments\remote\sdk\utils\XmlUtils;
use Doctrine\OXM\Mapping as DOM;

/**
 * <p>
 * Class representing a 3DSecure request to be sent to Realex.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example:
 * </p>
 * <p><code><pre>
 * $card = (new Card())
 *    ->addExpiryDate("0119")
 *    ->addNumber("420000000000000000")
 *    ->addType(CardType::VISA)
 *    ->addCardHolderName("Joe Smith")
 *    ->addCvn("123")
 *    ->addCvnPresenceIndicator(PresenceIndicator::CVN_PRESENT);
 *
 * $request = (new ThreeDSecureRequest())
 *    ->addAccount("yourAccount")
 *    ->addMerchantId("yourMerchantId")
 *    ->addType(ThreeDSecureType::VERIFY_ENROLLED)
 *  ->addAmount(100)
 *    ->addCurrency("EUR")
 *    ->addCard($card);
 * </pre></code></p>
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\threeDSecure
 *
 * @Dom\XmlRootEntity(xml="request")
 */
class ThreeDSecureRequest implements iRequest {

	/**
	 * @var string Format of timestamp is yyyyMMddhhmmss  e.g. 20150131094559 for 31/01/2015 09:45:59.
	 * If the timestamp is more than a day (86400 seconds) away from the server time, then the request is rejected.
	 *
	 * @Dom\XmlAttribute(type="string",name="timestamp")
	 */
	private $timeStamp;

	/**
	 * @var string The ThreeDSecure type.
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
	 * @var string Represents the unique order id of this transaction. Must be unique across all of the sub-accounts.
	 *
	 * @Dom\XmlText(type="string",name="orderid")
	 */
	private $orderId;

	/**
	 * @var Amount {@link Amount} object containing the amount value and the currency type.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\Amount",name="amount")
	 */
	private $amount;

	/**
	 * @var Card {@link Card} object containing the card details to be passed in request.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\Card", name="card")
	 */
	private $card;

	/**
	 * @var string The pre-encoded PaRes that you obtain from the Issuer's ACS.
	 *
	 * @Dom\XmlText(type="string",name="pares")
	 */
	private $pares;

	/**
	 * @var string Hash constructed from the time stamp, merchand ID, order ID, amount, currency, card number
	 * and secret values.
	 *
	 * @Dom\XmlText(type="string",name="sha1hash")
	 */
	private $hash;

	/**
	 * @var CommentCollection List of {@link Comment} objects to be passed in request.
	 * Optionally, up to two comments can be associated with any transaction.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\CommentCollection", name="comments")
	 */
	private $comments;

	/**
	 * ThreeDSecureRequest constructor.
	 */
	public function __construct() {
	}

	/**
	 * Getter for time stamp
	 *
	 * @return string
	 */
	public function getTimeStamp() {
		return $this->timeStamp;
	}

	/**
	 * Setter for time stamp
	 *
	 * @param string $timeStamp
	 */
	public function setTimeStamp( $timeStamp ) {
		$this->timeStamp = $timeStamp;
	}

	/**
	 * Getter for the ThreeDSecure type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Setter for the ThreeDSecure type
	 *
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter for merchant Id
	 *
	 * @return string
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Setter for merchant Id
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
	 * Getter for order Id
	 *
	 * @return string
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * Setter for order Id
	 *
	 * @param string $orderId
	 */
	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	/**
	 * Getter for {@link Amount}
	 *
	 * @return Amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Setter for {@link Amount}
	 *
	 * @param Amount $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	/**
	 * Getter for {@link Card}
	 *
	 * @return Card
	 */
	public function getCard() {
		return $this->card;
	}

	/**
	 * Setter for {@link Card}
	 *
	 * @param Card $card
	 */
	public function setCard( $card ) {
		$this->card = $card;
	}

	/**
	 * Getter for PaRes
	 *
	 * @return string
	 */
	public function getPares() {
		return $this->pares;
	}

	/**
	 * Setter for PaRes
	 *
	 * @param string $pares
	 */
	public function setPares( $pares ) {
		$this->pares = $pares;
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
	 * Helper method for adding a timeStamp
	 *
	 * @param string $timeStamp
	 *
	 * @return ThreeDSecureRequest
	 */
	public function addTimeStamp( $timeStamp ) {
		$this->timeStamp = $timeStamp;

		return $this;
	}

	/**
	 * Helper method for adding a type
	 *
	 * @param string $type
	 *
	 * @return ThreeDSecureRequest
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method for adding a {@link ThreeDSecureType}.
	 *
	 * @param ThreeDSecureType $type
	 *
	 * @return ThreeDSecureRequest
	 */
	public function addThreeDSecureType( ThreeDSecureType $type ) {
		$this->type = $type->getType();

		return $this;
	}


	/**
	 * Helper method for adding a merchantId
	 *
	 * @param string $merchantId
	 *
	 * @return ThreeDSecureRequest
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
	 * @return ThreeDSecureRequest
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
	 * @return ThreeDSecureRequest
	 */
	public function addOrderId( $orderId ) {
		$this->orderId = $orderId;

		return $this;
	}

	/**
	 * Helper method for adding an amount. If an {@link Amount} has not been set, then one is created.
	 *
	 * @param int $amount
	 *
	 * @return ThreeDSecureRequest
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
	 * Helper method for adding a currency. If an {@link Amount} has not been set, then one is created.
	 *
	 * @param string $currency
	 *
	 * @return ThreeDSecureRequest
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
	 * @return ThreeDSecureRequest
	 */
	public function addCard( $card ) {
		$this->card = $card;

		return $this;
	}

	/**
	 * Helper method for adding a pares
	 *
	 * @param string $pares
	 *
	 * @return ThreeDSecureRequest
	 */
	public function addPares( $pares ) {
		$this->pares = $pares;

		return $this;
	}

	/**
	 * Helper method for adding a hash
	 *
	 * @param string $hash
	 *
	 * @return ThreeDSecureRequest
	 */
	public function addHash( $hash ) {
		$this->hash = $hash;

		return $this;
	}

	/**
	 * Helper method for adding a comment. NB Only 2 comments will be accepted by Realex.
	 *
	 * @param string $comment
	 *
	 * @return ThreeDSecureRequest
	 */
	public function addComment( $comment ) {
		if ( is_null( $this->comments ) ) {
			$this->comments = new CommentCollection();
		}

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
	 * {@inheritDoc}
	 */
	public function toXml() {
		return XmlUtils::toXml( $this, new MessageType( MessageType::THREE_D_SECURE ) );
	}

	/**
	 * {@inheritDoc}
	 */
	public function fromXml( $xml ) {
		return XmlUtils::fromXml( $xml, new MessageType( MessageType::THREE_D_SECURE ) );
	}

	/**
	 * {@inheritDoc}
	 */
	public function generateDefaults( $secret ) {

		//generate timestamp if not set
		if ( is_null( $this->timeStamp) ) {
			$this->timeStamp = GenerationUtils::generateTimestamp();
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
	 * {@inheritDoc}
	 */
	public function responseFromXml( $xml ) {

		$response = new ThreeDSecureResponse();

		return $response->fromXml( $xml );
	}

	/**
	 * Creates the security hash from a number of fields and the shared secret.
	 *
	 * @param string $secret
	 *
	 * @return $this
	 */
	public function hash( $secret ) {

		//check for any null values and set them to empty string for hashing
		$timeStamp  = null == $this->timeStamp ? "" : $this->timeStamp;
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
}