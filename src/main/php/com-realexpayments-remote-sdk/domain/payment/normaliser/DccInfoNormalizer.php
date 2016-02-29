<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\DccInfo;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class DccInfoNormalizer extends SerializerAwareNormalizer implements NormalizerInterface, DenormalizerInterface {

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
		/** @var DccInfo $object */

		return array_filter( array(
			'ccp'      => $object->getDccProcessor(),
			'type'     => $object->getType(),
			'rate'     => $object->getRate(),
			'ratetype' => $object->getRateType(),
			'amount'   => $object->getAmount()
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
		if ( $format == "xml" && $data instanceof DccInfo ) {
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

		$data = new SafeArrayAccess( $data );


		$dccInfo = new DccInfo();
		$dccInfo->addDccProcessor( $data['ccp'] )
		        ->addType( $data['type'] )
		        ->addRate( $data['rate'] )
		        ->addRateType( $data['ratetype'] );

		$amount = $this->serializer->denormalize( $data['amount'], Amount::GetClassName(), $format, $context );
		if ( $amount != null ) {
			$dccInfo->setAmount( $amount );
		}

		return $dccInfo;

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
		if ( $format == "xml" && $type == DccInfo::GetClassName() ) {
			return true;
		}

		return false;
	}
}