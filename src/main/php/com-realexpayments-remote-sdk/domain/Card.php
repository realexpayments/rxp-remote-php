<?php

namespace com\realexpayments\remote\sdk\domain;

use Doctrine\OXM\Mapping as DOM;


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
 * $card = (new Card())->addExpiryDate("0119")
 *        ->addNumber("420000000000000000")
 *        ->addType(CardType.VISA)
 *        ->addCardHolderName("Joe Smith")
 *        ->addCvn("123")
 *        ->addCvnPresenceIndicator(PresenceIndicator.CVN_PRESENT);
 * </pre></code></p>
 *
 * @author vicpada
 *
 * @Dom\XmlEntity
 */
class Card {

	/* Masks for sanitising card numbers methods */
	const  SHORT_MASK = "******";

	/**
	 * @var string  The card number used for the transaction.
	 *
	 * @Dom\XmlText(type="string",name="number")
	 */
	private $number;

	/**
	 * @var string The card expiry date, in the format MMYY, which must be a date in the future.
	 *
	 * @Dom\XmlText(type="string",name="expdate")
	 */
	private $expiryDate;

	/**
	 * @var string The card holder's name
	 *
	 * @Dom\XmlText(type="string",name="chname")
	 */
	private $cardHolderName;

	/**
	 * @var string The card type used in the transaction.
	 *
	 * @Dom\XmlText(type="string",name="type")
	 */
	private $type;


	/**
	 * @var int The card issue number.
	 *
	 * @Dom\XmlText(type="string",name="issueno")
	 */
	private $issueNumber;


	/**
	 * @var CVN The card verification number.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\CVN",name="cvn")
	 */
	private $cvn;

	/**
	 * Card constructor.
	 */
	public function __construct() {
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