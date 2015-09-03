<?php


namespace com\realexpayments\remote\sdk\domain\payment;




/**
 * <p>
 *  If you are configured for recurring/continuous authority transactions, you must set the recurring values.
 * </p>
 *
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p><code>
 *	$recurring = (new Recurring())
 *		->addFlag(RecurringFlag::ONE);
 *	
 *	$recurring = (new Recurring())
 *		->addSequence(RecurringSequence::FIRST)
 *		->addType(RecurringType::FIXED);
 *
 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\payment
 *
 */
class Recurring {

	/**
	 * @var string Type can be either fixed or variable depending on whether you will be changing the amounts or not.
	 *
	 */
	private $type;

	/**
	 * @var string The recurring sequence. Must be first for the first transaction for this card,
	 * subsequent for transactions after that, and last for the final transaction of the set.
	 * Only supported by some acquirers.
	 *
	 */
	private $sequence;

	/**
	 * @var string The recurring flag. Optional field taking values 0, 1 or 2.
	 *
	 */
	private $flag;

	/**
	 * Recurring constructor.
	 */
	public function __construct() {
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
	 * Getter for sequence
	 *
	 * @return string
	 */
	public function getSequence() {
		return $this->sequence;
	}

	/**
	 * Setter for sequence
	 *
	 * @param string $sequence
	 */
	public function setSequence( $sequence ) {
		$this->sequence = $sequence;
	}

	/**
	 * Getter for flag
	 *
	 * @return string
	 */
	public function getFlag() {
		return $this->flag;
	}

	/**
	 * Setter for flag
	 *
	 * @param string $flag
	 */
	public function setFlag( $flag ) {
		$this->flag = $flag;
	}


	/**
	 * Helper method for adding a type
	 *
	 * @param RecurringType|string $type
	 *
	 * @return Recurring
	 */
	public function addType( $type ) {
		if ( $type instanceof RecurringType ) {
			$this->type = $type->getType();
		} else {
			$this->type = $type;
		}

		return $this;
	}


	/**
	 * Helper method for adding a sequence
	 *
	 * @param RecurringSequence|string $sequence
	 *
	 * @return Recurring
	 */
	public function addSequence( $sequence ) {
		if ( $sequence instanceof RecurringSequence ) {
			$this->sequence = $sequence->getSequence();
		} else {
			$this->sequence = $sequence;
		}

		return $this;
	}

	/**
	 * Helper method for adding a flag
	 *
	 * @param RecurringFlag|string $flag
	 *
	 * @return Recurring
	 */
	public function addFlag( $flag ) {
		if ( $flag instanceof RecurringFlag ) {
			$this->flag = $flag->getRecurringFlag();
		} else {
			$this->flag = $flag;
		}

		return $this;
	}
}