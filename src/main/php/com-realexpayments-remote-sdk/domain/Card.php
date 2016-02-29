<?php

namespace com\realexpayments\remote\sdk\domain;




/**
 * <p>
 * Represents the card which is required in AUTH requests.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example creation:
 * </p>
 * <p><code><pre>
 * $card = (new Card())
 * 	  ->addType(CardType::VISA)
 * 	  ->addNumber("4242424242424242")
 * 	  ->addExpiryDate("0525")
 *    ->addCvn("123")
 *    ->addCvnPresenceIndicator(PresenceIndicator::CVN_PRESENT);
 *    ->addCardHolderName("Joe Bloggs");
 * </pre></code></p>
 *
 * @author vicpada
 *
 */
class Card {

	/* Masks for sanitising card numbers methods */
	const  SHORT_MASK = "******";

	/**
	 * @var string  The card number used for the transaction.
	 *
	 */
	private $number;

	/**
	 * @var string The card expiry date, in the format MMYY, which must be a date in the future.
	 *
	 */
	private $expiryDate;

	/**
	 * @var string The card holder's name
	 *
	 */
	private $cardHolderName;

	/**
	 * @var string The card type used in the transaction.
	 *
	 */
	private $type;


	/**
	 * @var int The card issue number.
	 *
	 */
	private $issueNumber;


	/**
	 * @var CVN The card verification number.
	 *
	 */
	private $cvn;

	/**
	 * @var String The reference for this card (Card Storage)
	 *
	 * This must be unique within the Payer record if you are adding multiple
	 * cards, but it does not need to be unique in relation to other Payers.
	 */
	private $reference;

	/**
	 * @var String The payer ref for this customer  (Card Storage)
	 *
	 */
	private $payerReference;

	/**
	 * Card constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for the card number.
	 *
	 * @return string The card number
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * Setter for the card number.
	 *
	 * @param string $number
	 */
	public function setNumber( $number ) {
		$this->number = $number;
	}

	/**
	 * Getter for the expiry date
	 *
	 * @return string The expiry date
	 */
	public function getExpiryDate() {
		return $this->expiryDate;
	}

	/**
	 * Setter for the expiry date
	 *
	 * @param string $expiryDate
	 */
	public function setExpiryDate( $expiryDate ) {
		$this->expiryDate = $expiryDate;
	}

	/**
	 * Getter for the card holder name
	 *
	 * @return string The card holder name
	 */
	public function getCardHolderName() {
		return $this->cardHolderName;
	}

	/**
	 * Setter for  card holder name
	 *
	 * @param string $cardHolderName
	 */
	public function setCardHolderName( $cardHolderName ) {
		$this->cardHolderName = $cardHolderName;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter for the issue number
	 *
	 * @return int
	 */
	public function getIssueNumber() {
		return $this->issueNumber;
	}

	/**
	 * Setter for the issue number
	 *
	 * @param int $issueNumber
	 */
	public function setIssueNumber( $issueNumber ) {
		$this->issueNumber = $issueNumber;
	}

	/**
	 * Getter for the CVN
	 *
	 * @return CVN
	 */
	public function getCvn() {
		return $this->cvn;
	}

	/**
	 * Setter for the CVN
	 *
	 * @param CVN $cvn
	 */
	public function setCvn( $cvn ) {
		$this->cvn = $cvn;
	}

	/**
	 * Getter for reference
	 *
	 * @return String
	 */
	public function getReference() {
		return $this->reference;
	}

	/**
	 * Setter for reference
	 *
	 * @param String $reference
	 */
	public function setReference( $reference ) {
		$this->reference = $reference;
	}

	/**
	 * Getter for payerReference
	 *
	 * @return String
	 */
	public function getPayerReference() {
		return $this->payerReference;
	}

	/**
	 * Setter for payerReference
	 *
	 * @param String $payerReference
	 */
	public function setPayerReference( $payerReference ) {
		$this->payerReference = $payerReference;
	}

	/**
	 * Helper method to add a card number.
	 *
	 * @param string $number
	 *
	 * @return Card
	 */
	public function addNumber( $number ) {
		$this->number = $number;

		return $this;
	}

	/**
	 * Helper method to add CVN. If the {@link Cvn} field is null then one is created.
	 *
	 * @param string $cvn
	 *
	 * @return $this
	 */
	public function addCvn( $cvn ) {

		if ( is_null( $this->cvn ) ) {
			$this->cvn = new CVN();
			$this->cvn->addNumber( $cvn );
		} else {
			$this->cvn->addNumber( $cvn );
		}

		return $this;
	}


	/**
	 * Helper method to add CVN presence indicator. If the {@link Cvn} is null then one is created.
	 * <p>
	 * <code><pre>
	 * $card = new Card();
	 * $card->addCvnPresenceIndicator(PresenceIndicator::CVN_PRESENT);
	 * </code></pre>
	 * </p>
	 *
	 * @param PresenceIndicator|string $presenceIndicator
	 *
	 * @return $this
	 */
	public function  addCvnPresenceIndicator( $presenceIndicator ) {

		if ( is_null( $this->cvn ) ) {
			$this->cvn = new CVN();
			$this->cvn->addPresenceIndicator( $presenceIndicator );
		} else {
			$this->cvn->addPresenceIndicator( $presenceIndicator );
		}


		return $this;
	}


	/**
	 * Helper method to add a card expiry date. The format is MMYY e.g. 1219 for December 2019.
	 *
	 * @param string $expiryDate
	 *
	 * @return $this
	 */
	public function addExpiryDate( $expiryDate ) {
		$this->expiryDate = $expiryDate;

		return $this;
	}

	/**
	 * Helper method to add an issue number.
	 *
	 * @param integer $issueNumber
	 *
	 * @return $this
	 */
	public function addIssueNumber( $issueNumber ) {
		$this->issueNumber = $issueNumber;

		return $this;
	}

	/**
	 * Helper method to add a card holder name.
	 *
	 * @param string $cardHolderName
	 *
	 * @return $this
	 */
	public function addCardHolderName( $cardHolderName ) {
		$this->cardHolderName = $cardHolderName;

		return $this;
	}

	/**
	 * Helper method to add a card type.
	 *
	 * @param string $type
	 *
	 * @return $this
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method to add a {@link CardType}.
	 *
	 * @param CardType $type
	 *
	 * @return $this
	 */
	public function addCardType( CardType $type ) {
		$this->type = $type->getType();

		return $this;
	}

	/**
	 * Helper method for adding a payerReference
	 *
	 * @param String $payerReference
	 *
	 * @return Card
	 */
	public function addPayerReference( $payerReference ) {
		$this->payerReference = $payerReference;

		return $this;
	}

	/**
	 * Helper method for adding a reference
	 *
	 * @param String $reference
	 *
	 * @return Card
	 */
	public function addReference( $reference ) {
		$this->reference = $reference;

		return $this;
	}


	/**
	 * Get a sanitised version of the card number displaying only the first six and last four digits.
	 *
	 * @return string
	 */
	public function displayFirstSixLastFour() {

		$result = substr( $this->number, 0, 6 );
		$result .= $this::SHORT_MASK;
		$result .= substr( $this->number, strlen( $this->number ) - 4 );

		return $result;
	}
}