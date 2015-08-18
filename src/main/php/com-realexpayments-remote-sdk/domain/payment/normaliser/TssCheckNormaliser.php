<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\payment\TssResultCheck;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TssCheckNormaliser implements NormalizerInterface {

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

		/** @var TssResultCheck $object */
		return array(
			'@id' => $object->getId(),
			'#'   => $object->getValue()
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
		if ( $format == "xml" && $data instanceof TssResultCheck ) {
			return true;
		}

		return false;
	}
}