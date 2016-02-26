<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\DccInfoResult;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DccInfoResultNormalizer implements NormalizerInterface, DenormalizerInterface {

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
		/** @var DccInfoResult $object */

		return array_filter( array(
			'cardholdercurrency'          => $object->getCardHolderCurrency(),
			'cardholderamount'            => $object->getCardHolderAmount(),
			'cardholderrate'              => $object->getCardHolderRate(),
			'merchantcurrency'            => $object->getMerchantCurrency(),
			'merchantamount'              => $object->getMerchantAmount(),
			'marginratepercentage'        => $object->getMarginRatePercentage(),
			'exchangeratesourcename'      => $object->getExchangeRateSourceName(),
			'commissionpercentage'        => $object->getCommissionPercentage(),
			'exchangeratesourcetimestamp' => $object->getExchangeRateSourceTimestamp()
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
		if ( $format == "xml" && $data instanceof DccInfoResult ) {
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

		$dccInfoResult = new DccInfoResult();
		$dccInfoResult->addCardHolderCurrency( $data['cardholdercurrency'] )
		              ->addCardHolderAmount( $data['cardholderamount'] )
		              ->addCardHolderRate( $data['cardholderrate'] )
		              ->addMerchantCurrency( $data['merchantcurrency'] )
		              ->addMerchantAmount( $data['merchantamount'] )
		              ->addMarginRatePercentage( $data['marginratepercentage'] )
		              ->addExchangeRateSourceName( $data['exchangeratesourcename'] )
		              ->addCommissionPercentage( $data['commissionpercentage'] )
		              ->addExchangeRateSourceTimestamp( $data['exchangeratesourcetimestamp'] );

		return $dccInfoResult;
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
		if ( $format == "xml" && $type == DccInfoResult::GetClassName() ) {
			return true;
		}

		return false;
	}
}