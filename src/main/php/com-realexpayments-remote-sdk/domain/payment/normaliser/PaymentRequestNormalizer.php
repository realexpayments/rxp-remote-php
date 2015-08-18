<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PaymentRequestNormalizer extends AbstractNormalizer {

	/**
	 * Denormalizes data back into an object of the given class.
	 *
	 * @param mixed $data data to restore
	 * @param string $class the expected class to instantiate
	 * @param string $format format the given data was extracted from
	 * @param array $context options available to the denormalizer
	 *
	 * @return object
	 */
	public function denormalize( $data, $class, $format = null, array $context = array() ) {
		return new PaymentRequest();
	}

	/**
	 * Checks whether the given class is supported for denormalization by this normalizer.
	 *
	 * @param mixed $data Data to denormalize from.
	 * @param string $type The class to which the data should be denormalized.
	 * @param string $format The format being deserialized from.
	 *
	 * @return bool
	 */
	public function supportsDenormalization( $data, $type, $format = null ) {
		if ( $format == "xml" && $type == 'com\realexpayments\remote\sdk\domain\payment\PaymentRequest' ) {
			return true;
		}

		return false;
	}

	/**
	 * Normalizes an object into a set of arrays/scalars.
	 *
	 * @param object $object object to normalize
	 * @param string $format format the normalization result will be encoded as
	 * @param array $context Context options for the normalizer
	 *
	 * @return array|string|bool|int|float|null
	 */
	public function normalize( $object, $format = null, array $context = array() ) {

		/** @var PaymentRequest $object */

		$comments = $object->getComments();
		if ( is_null( $comments ) ) {
			$comments = array();
		} else {
			$comments = $comments->getComments();
		}


		return array(
			'@timestamp'  => $object->getTimestamp(),
			'@type'       => $object->getType(),
			'merchantid'  => $object->getMerchantId(),
			'account'     => $object->getAccount(),
			'channel'     => $object->getChannel(),
			'orderid'     => $object->getOrderId(),
			'amount'      => $this->normaliseAmount( $object ),
			'card'        => $this->normaliseCard( $object ),
			'autoSettle'  => $this->normaliseAutoSettle( $object ),
			'sha1hash'    => $object->getHash(),
			'comments'    => array( 'comments' => $comments ),
			'pasref'      => $object->getPaymentsReference(),
			'authcode'    => $object->getAuthCode(),
			'refundhash'  => $object->getRefundHash(),
			'fraudfilter' => $object->getFraudFilter(),
			'recurring'   => $this->normaliseRecurring( $object ),
			'tssinfo'     => $this->normaliseTssInfo( $object ),
			'mpi'         => $this->normaliseMpi( $object ),
		);
	}

	/**
	 * Checks whether the given class is supported for normalization by this normalizer.
	 *
	 * @param mixed $data Data to normalize.
	 * @param string $format The format being (de-)serialized from or into.
	 *
	 * @return bool
	 */
	public function supportsNormalization( $data, $format = null ) {
		if ( $format == "xml" && $data instanceof PaymentRequest ) {
			return true;
		}

		return false;
	}

	private function normaliseAmount( PaymentRequest $request ) {
		$amount = $request->getAmount();
		if ( is_null( $amount ) ) {
			return array();
		}

		return array(
			'@currency' => $amount->getCurrency(),
			'#'         => $amount->getAmount()
		);
	}

	private function normaliseCard( PaymentRequest $request ) {
		$card = $request->getCard();
		if ( is_null( $card ) ) {
			return array();
		}

		return array(
			'number'  => $card->getNumber(),
			'expdate' => $card->getExpiryDate(),
			'chname'  => $card->getCardHolderName(),
			'type'    => $card->getType(),
			'issueno' => $card->getIssueNumber(),
			'cvn'     => $this->normaliseCVN( $card )
		);
	}

	private function normaliseAutoSettle( PaymentRequest $request ) {
		$autoSettle = $request->getAutoSettle();
		if ( is_null( $autoSettle ) ) {
			return array();
		}

		return array(
			'@flag' => $autoSettle->getFlag()
		);
	}

	private function normaliseRecurring( PaymentRequest $request ) {

		$recurring = $request->getRecurring();
		if ( is_null( $recurring ) ) {
			return array();
		}

		return array(
			'@flag'     => $recurring->getFlag(),
			'@sequence' => $recurring->getSequence(),
			'@type'     => $recurring->getType()
		);
	}

	private function normaliseTssInfo( PaymentRequest $request ) {
		$tssInfo = $request->getTssInfo();
		if ( is_null( $tssInfo ) ) {
			return array();
		}

		return array(
			'custnum'       => $tssInfo->getCustomerNumber(),
			'prodid'        => $tssInfo->getProductId(),
			'varref'        => $tssInfo->getVariableReference(),
			'custipaddress' => $tssInfo->getCustomerIpAddress(),
			'address'       => $tssInfo->getAddresses()
		);
	}

	private function normaliseMpi( PaymentRequest $request ) {
		$mpi = $request->getMpi();
		if ( is_null( $mpi ) ) {
			return array();
		}

		return array(
			'cavv' => $mpi->getCavv(),
			'xid'  => $mpi->getXid(),
			'eci'  => $mpi->getEci()
		);
	}

	private function normaliseCVN( Card $card ) {
		$cvn = $card->getCvn();
		if ( is_null( $cvn ) ) {
			return array();
		}

		return array(
			'number'  => $cvn->getNumber(),
			'presind' => $cvn->getPresenceIndicator()
		);
	}
}