<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\Country;
use com\realexpayments\remote\sdk\domain\PayerAddress;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class PayerAddressNormalizer extends SerializerAwareNormalizer implements NormalizerInterface, DenormalizerInterface {
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
		/** @var PayerAddress $object */

		return array_filter( array(
			'line1'    => $object->getLine1(),
			'line2'    => $object->getLine2(),
			'line3'    => $object->getLine3(),
			'city'     => $object->getCity(),
			'county'   => $object->getCounty(),
			'postcode' => $object->getPostcode(),
			'country'  => $object->getCountry()

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
		if ( $format == "xml" && $data instanceof PayerAddress ) {
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

		$address = new  PayerAddress();
		$address->addLine1($data['line1'])
			->addLine2($data['line2'])
			->addLine3($data['line3'])
			->addCity($data['city'])
			->addCounty($data['county'])
			->addPostcode($data['postcode']);
			$address->setCountry( $this->denormalizeCountry($data));

		return $address;
	}

	private function denormalizeCountry( $data ) {
		return $this->serializer->denormalize( $data['country'], Country::GetClassName(), $this->format, $this->context );
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
		if ( $format == "xml" && $type == PayerAddress::GetClassName() ) {
			return true;
		}

		return false;
	}
}