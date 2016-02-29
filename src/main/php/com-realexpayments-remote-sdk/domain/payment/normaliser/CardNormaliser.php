<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class CardNormaliser extends SerializerAwareNormalizer implements NormalizerInterface, DenormalizerInterface {
	private $format;
	private $context;
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
		/** @var Card $object */

		if ( is_null( $object ) ) {
			return array();
		}

		return array_filter( array(
			'number'   => $object->getNumber(),
			'expdate'  => $object->getExpiryDate(),
			'chname'   => $object->getCardHolderName(),
			'type'     => $object->getType(),
			'issueno'  => $object->getIssueNumber(),
			'ref'      => $object->getReference(),
			'payerref' => $object->getPayerReference(),
			'cvn'      => $object->getCvn(),
		), array( NormaliserHelper::GetClassName(), "filter_data" ) );
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
		if ( $format == "xml" && $data instanceof Card ) {
			return true;
		}

		return false;
	}

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
		if ( is_null( $data ) ) {
			return null;
		}

		$this->format  = $format;
		$this->context = $context;

		$data = new SafeArrayAccess( $data );

		$card = new Card();

		$card->addNumber( $data['number'] )
		     ->addExpiryDate( $data['expdate'] )
		     ->addCardHolderName( $data['chname'] )
		     ->addType( $data['type'] )
		     ->addIssueNumber( $data['issueno'] )
			->addReference($data['ref'] )
			->addPayerReference($data['payerref']);

		$cvn = $this->denormaliseCVN( $data );
		$card->setCvn( $cvn );

		return $card;
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
		if ( $format == "xml" && $type == Card::GetClassName() ) {
			return true;
		}

		return false;
	}

	private function denormaliseCVN( $data ) {
		return $this->serializer->denormalize( $data['cvn'], Cvn::GetClassName(), $this->format, $this->context );
	}
}