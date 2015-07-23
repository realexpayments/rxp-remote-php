<?php


namespace com\realexpayments\remote\sdk\domain;
use Doctrine\OXM\Mapping as DOM;


/**
 * <p>
 * Class representing the card verification details.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example creation:
 * </p>
 * <p><code><pre>
 * $cvn = (new Cvn())->addNumber(123)->addPresenceIndicator(PresenceIndicator.CVN_PRESENT);
 * </pre></code></p>
 *
 * @author vicpada
 *
 * @Dom\XmlEntity
 */
class CVN {


	/**
	 * A three-digit number on the reverse of the card. It is called the CVC for VISA and the CVV2 for MasterCard.
	 * For an AMEX card, it is a four digit number.
	 *
	 * @var integer The number
	 *
	 * @Dom\XmlText(type="integer",name="number")
	 */
	private $number;


	/**
	 * <p>
	 * Presence indicator. 4 values are permitted:
	 * <ol>
	 * <li>cvn present</li>
	 * <li>cvn illegible</li>
	 * <li>cvn not on card</li>
	 * <li>cvn not requested</li>
	 * </ol>
	 * </p>
	 *
	 * @var string Presence Indicator
	 *
	 * @Dom\XmlText(type="string",name="presind")
	 */
	private $presenceIndicator;



	/**
	 * CVN constructor.
	 */
	public function __construct() {
	}


	/**
	 * Getter for the verification number.
	 *
	 * @return int
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * Setter for the verification number
	 *
	 * @param int $number
	 */
	public function setNumber( $number ) {
		$this->number = $number;
	}

	/**
	 * Getter for the presence indicator
	 *
	 * @return string
	 */
	public function getPresenceIndicator() {
		return $this->presenceIndicator;
	}

	/**
	 * Setter for the presence indicator
	 *
	 * @param string $presenceIndicator
	 */
	public function setPresenceIndicator( $presenceIndicator ) {
		$this->presenceIndicator = $presenceIndicator;
	}

	/**
	 * Helper method to add a verification number.
	 *
	 * @param int $number
	 *
	 * @return $this
	 */
	public function addNumber( $number ) {
		$this->number = $number;

		return $this;
	}

	/**
	 * Helper method to add a presence indicator.
	 *
	 * @param string $presenceIndicator
	 *
	 * @return $this
	 */
	public function addPresenceIndicator( $presenceIndicator ) {
		$this->presenceIndicator = $presenceIndicator;

		return $this;
	}


	/**
	 * Helper method to add a presence indicator.
	 *
	 * @param PresenceIndicator $presenceIndicator
	 *
	 * @return $this
	 */
	public function addPresenceIndicatorType( PresenceIndicator $presenceIndicator ) {
		$this->presenceIndicator = $presenceIndicator;

		return $this;
	}

}