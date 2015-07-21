<?php


namespace com\realexpayments\remote\sdk\domain\payment;
use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;

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
 */
class PaymentRequest {


	/**
	 * @var string Format of timestamp is yyyyMMddhhmmss  e.g. 20150131094559 for 31/01/2015 09:45:59.
	 * If the timestamp is more than a day (86400 seconds) away from the server time, then the request is rejected.
	 */
	private $timestamp;

	/**
	 * @var string The payment type
	 */
	private $type;

	/**
	 * @var string Represents Realex Payments assigned merchant id.
	 */
	private $merchantId;

	/**
	 * @var string Represents the Realex Payments subaccount to use. If this element is omitted, then the
	 * default account is used.
	 */
	private $account;

	/**
	 * @var string For certain acquirers it is possible to specify whether a transaction is to be processed as a
	 * Mail Order/Telephone Order or Ecommerce transaction. For other banks, this is configured on the
	 * Merchant ID level.
	 */
	private $channel;

	/**
	 * @var string Represents the unique order id of this transaction. Must be unique across all of the sub-accounts.
	 */
	private $orderId;

	/**
	 * @var Amount Object containing the amount value and the currency type.
	 */
	private $amount;

	/**
	 * @var Card Object containing the card details to be passed in request.
	 */
	private $card;

	/**
	 * @var AutoSettle Object containing the auto settle flag.
	 */
	private $autoSettle;

	/**
	 * @var string Hash constructed from the time stamp, merchand ID, order ID, amount, currency, card number
	 * and secret values.
	 */
	private $hash;

	/**
	 * @var array List of {@link Comment} objects to be passed in request. Optionally, up to two comments
	 * can be associated with any transaction.
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
	 */
	private $authCode;

	/**
	 * @var string Represents a hash of the refund password, which Realex Payments will provide. The SHA1
	 * algorithm must be used to generate this hash.
	 */
	private $refundHash;

	/**
	 * @var string TODO - info on this
	 */
	private $fraudFilter;

	/**
	 * @var Recurring If you are configured for recurring/continuous authority transactions, you must set the recurring values.
	 */
	private $recurring;

	/**
	 * @var TssInfo Contains optional variables which can be used to identify customers in the
	 * Realex Payments system.
	 */
	private $tssInfo;

	/**
	 * @var Mpi Contains 3D Secure/Secure Code information if this transaction has used a 3D
	 * Secure/Secure Code system, either Realex's RealMPI or a third party's.
	 */
	private $mpi;

	/**
	 * Constructor for Payment Request
	 */
	public function __construct()
	{

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


}