<?php

namespace com\realexpayments\remote\sdk\domain;


class Card {

	const  SHORT_MASK = "******";

	private  $number;

	/**
	 * @return mixed
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param mixed $number
	 */
	public function setNumber( $number ) {
		$this->number = $number;
	}

	/**
	 * Card constructor.
	 */
	public function __construct() {
	}

	public function displayFirstSixLastFour() {

		$result = substr($this->number,0,6);
		$result .= $this::SHORT_MASK;
		$result .= substr($this->number, strlen($this->number) - 4);

		return $result;
	}

}