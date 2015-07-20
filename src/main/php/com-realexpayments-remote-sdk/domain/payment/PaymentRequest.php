<?php


namespace com\realexpayments\remote\sdk\domain\payment;


	/**
	 * Class PaymentRequest
	 *
	 * <p>
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
	 *	->addExpiryDate("0119")
	 * 	->addNumber("420000000000000000")
	 * 	->addType(CardType.VISA)
	 * 	->addCardHolderName("Joe Smith")
	 * 	->addCvn(123)
	 * 	->addCvnPresenceIndicator(PresenceIndicator.CVN_PRESENT);
	 *
	 * $request = (new PaymentRequest())
	 * 	->addAccount("yourAccount")
	 * 	->addMerchantId("yourMerchantId")
	 *	->addType(PaymentType.AUTH)
	 *	->addAmount(100)
	 *	->addCurrency("EUR")
	 *	->addCard(card)
	 *	->addAutoSettle(new AutoSettle()->addFlag(AutoSettleFlag.TRUE));
	 * </pre></code></p>
	 *
	 * <p>
	 * Example AUTH with Address Verification:
	 * <p>
	 * <p><code><pre>
	 * $card = (new Card())
	 *	->addExpiryDate("0119")
	 * 	->addNumber("420000000000000000")
	 * 	->addType(CardType.VISA)
	 * 	->addCardHolderName("Joe Smith")
	 * 	->addCvn(123)
	 * 	->addCvnPresenceIndicator(PresenceIndicator.CVN_PRESENT);
	 *
	 * $request = (new PaymentRequest())
	 * 	->addAccount("yourAccount")
	 * 	->addMerchantId("yourMerchantId")
	 *	->addType(PaymentType.AUTH)
	 *	->addAmount(100)
	 *	->addCurrency("EUR")
	 *	->addCard(card)
	 *	->addAutoSettle(new AutoSettle().addFlag(AutoSettleFlag.TRUE))
	 *	->addAddressVerificationServiceDetails("382 The Road", "WB1 A42");
	 * </pre></code></p>
	 *
	 * @package com\realexpayments\remote\sdk\domain\payment
	 *
	 * * @author vicpada
	 */
/**
 * Class PaymentRequest
 * @package com\realexpayments\remote\sdk\domain\payment
 */
class PaymentRequest {

	/**
	 * @var TssInfo Contains optional variables which can be used to identify customers in the
	 * Realex Payments system.
	 */
	private $tssInfo;

	/**
	 * <p>
	 * This helper method adds Address Verification Service (AVS) fields to the request.
	 * </p>
	 * <p>
	 * The Address Verification Service (AVS) verifies the cardholder's address by checking the
	 * information provided by at the time of sale against the issuing bank's records.
	 * </p>
	 * @param string $addressLine line of the address e.g. 123 Fake St
	 * @param string $postcode The UK postcode e.g. WB1 A42
	 *
	 * @return $this
	 */
	public function addAddressVerificationServiceDetails( $addressLine, $postcode ) {

		//build code in format <digits from postcode>|<digits from address>
		$postcodeDigits    = preg_replace( "/\\D+/", "", $postcode );
		$addressLineDigits = preg_replace( "/\\D+/", "", $addressLine );

		$code = $postcodeDigits . "|" . $addressLineDigits;

		//construct billing address from code
		$address = ( new Address() )->addCode( $code )
		                            ->addType( AddressType::Billing );

		//add address to TSS Info
		if ( is_null( $this->tssInfo ) ) {
			$this->tssInfo = new TssInfo();
		}

		$this->tssInfo->addAddress( $address );

		return $this;
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
	 * @return $this
	 */
	public function addTssInfo( TssInfo $tssInfo ) {
		$this->tssInfo = $tssInfo;

		return $this;
	}


}