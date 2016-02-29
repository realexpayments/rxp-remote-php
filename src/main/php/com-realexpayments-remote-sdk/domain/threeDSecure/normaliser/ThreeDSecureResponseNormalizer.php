<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure\normaliser;


use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecure;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureResponse;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ThreeDSecureResponseNormalizer extends AbstractNormalizer {

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
		$response = new ThreeDSecureResponse();
		$array    = new SafeArrayAccess( $data );

		$response->setTimeStamp( $array['@timestamp'] );
		$response->setMerchantId( $array['merchantid'] );
		$response->setAccount( $array['account'] );
		$response->setOrderId( $array['orderid'] );
		$response->setResult( $array['result'] );
		$response->setAuthCode( $array['authcode'] );
		$response->setMessage( $array['message'] );
		$response->setPaymentsReference( $array['pasref'] );
		$response->setTimeTaken( $array['timetaken'] );
		$response->setAuthTimeTaken( $array['authtimetaken'] );
		$response->setPareq( $array['pareq'] );
		$response->setUrl( $array['url'] );
		$response->setEnrolled( $array['enrolled'] );
		$response->setXid( $array['xid'] );
		$response->setThreeDSecure( $this->denormaliseThreeDSecure( $array ) );
		$response->setHash( $array['sha1hash'] );


		return $response;
	}

	private function denormaliseThreeDSecure( $array ) {
		$threedsecureData = $array['threedsecure'];


		if ( ! isset( $threedsecureData ) || ! is_array( $threedsecureData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $threedsecureData );

		$threeDSecure = new ThreeDSecure();

		$threeDSecure->addStatus( $data['status'] )
		             ->addEci( $data['eci'] )
		             ->addXid( $data['xid'] )
		             ->addCavv( $data['cavv'] )
		             ->addAlgorithm( $data['algorithm'] );

		return $threeDSecure;
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
		if ( $format == "xml" && $type == ThreeDSecureResponse::GetClassName() ) {
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
		/** @var ThreeDSecureResponse $object */

		return array_filter(
			array(
				'@timestamp'    => $object->getTimestamp(),
				'merchantid'    => $object->getMerchantId(),
				'account'       => $object->getAccount(),
				'orderid'       => $object->getOrderId(),
				'result'        => $object->getResult(),
				'authcode'      => $object->getAuthCode(),
				'message'       => $object->getMessage(),
				'pasref'        => $object->getPaymentsReference(),
				'timetaken'     => $object->getTimeTaken(),
				'authtimetaken' => $object->getAuthTimeTaken(),
				'pareq'         => $object->getPareq(),
				'url'           => $object->getUrl(),
				'enrolled'      => $object->getEnrolled(),
				'xid'           => $object->getXid(),
				'threedsecure'  => $this->normaliseThreedsecure( $object ),
				'sha1hash'      => $object->getHash()
			) );
	}

	private function normaliseThreedsecure( ThreeDSecureResponse $response ) {
		$threeDSecure = $response->getThreeDSecure();
		if ( is_null( $threeDSecure ) ) {
			return array();
		}

		return array_filter( array(
			'status'    => $threeDSecure->getStatus(),
			'eci'       => $threeDSecure->getEci(),
			'xid'       => $threeDSecure->getXid(),
			'cavv'      => $threeDSecure->getCavv(),
			'algorithm' => $threeDSecure->getAlgorithm()
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
		if ( $format == "xml" && $data instanceof ThreeDSecureResponse ) {
			return true;
		}

		return false;
	}


}