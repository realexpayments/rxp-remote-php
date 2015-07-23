<?php


namespace com\realexpayments\remote\sdk\domain\payment;
use Doctrine\OXM\Mapping as DOM;

/**
 * <p>
 * Class representing the AutoSettle flag in a Realex request. If set to TRUE (1),
 * then the transaction will be included in today's settlement file. If set to FALSE (0), then the
 * transaction will be authorised but not settled. Merchants must manually settle delayed
 * transactions within 28 days of authorisation.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example creation:
 * </p>
 * <p><code><pre>
 * AutoSettle autoSettle = new AutoSettle().addFlag(AutoSettleTrue.TRUE);
 * </pre></code></p>
 *
 * @author vicpada
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 *
 * @Dom\XmlEntity
 */
class AutoSettle {

	/**
	 * @var string The AutoSettle flag value.
	 *
	 * @Dom\XmlAttribute(type="string",name="flag")
	 */
	private $flag;

	/**
	 * AutoSettle constructor.
	 */
	public function __construct() {
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
	 * Helper method for adding the flag value
	 *
	 * @param string $flag
	 * @return AutoSettle
	 */
	public function addFlag( $flag ) {
		$this->flag = $flag;

		return $this;
	}

	/**
	 * Helper method for adding the {@link AutoSettle} value.
	 *
	 * @param AutoSettleFlag $flag
	 * @return AutoSettle
	 */
	public function addAutoSettleFlag( AutoSettleFlag $flag ) {
		$this->flag = $flag->getFlag();
		return $this;
	}
}

