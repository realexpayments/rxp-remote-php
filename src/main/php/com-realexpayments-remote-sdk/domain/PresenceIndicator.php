<?php


namespace com\realexpayments\remote\sdk\domain;


use com\realexpayments\remote\sdk\EnumBase;

/**
 * <p>
 * Enumeration of the possible presence indicator values. 4 values are permitted:
 * <ol>
 * <li>cvn present</li>
 * <li>cvn illegible</li>
 * <li>cvn not on card</li>
 * <li>cvn not requested</li>
 * </ol>
 * </p>
 */
class PresenceIndicator extends EnumBase{

	const __default = self::CVN_PRESENT;

	const CVN_PRESENT = "1";
	const CVN_ILLEGIBLE = "2";
	const CVN_NOT_ON_CARD = "3";
	const CVN_NOT_REQUESTED = "4";

	/**
	 * @var string The indicator
	 */
	private $indicator;


	/**
	 * Constructor for the enum
	 *
	 * @param string $indicator
	 */
	public function __construct($indicator)
	{
		parent::__construct($indicator);
		$this->indicator = $indicator;
	}

	/**
	 * Getter for the indicator
	 *
	 * @return string
	 */
	public function getIndicator() {
		return $this->indicator;
	}


}