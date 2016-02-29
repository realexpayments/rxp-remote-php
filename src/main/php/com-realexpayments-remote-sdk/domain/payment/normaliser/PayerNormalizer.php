<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\Payer;
use com\realexpayments\remote\sdk\domain\PayerAddress;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;
use com\realexpayments\remote\sdk\domain\PhoneNumbers;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class PayerNormalizer extends SerializerAwareNormalizer implements NormalizerInterface, DenormalizerInterface {
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
		/** @var Payer $object */

		$hasComments = true;
		$comments    = $object->getComments();
		if ( is_null( $comments ) || $comments->getSize() == 0 ) {
			$hasComments = false;
		} else {
			$comments = $comments->getComments();
		}

		return array_filter( array(
			'@type'        => $object->getType(),
			'@ref'         => $object->getRef(),
			'title'        => $object->getTitle(),
			'firstname'    => $object->getFirstName(),
			'surname'      => $object->getSurname(),
			'company'      => $object->getCompany(),
			'address'      => $object->getAddress(),
			'phonenumbers' => $object->getPhoneNumbers(),
			'email'        => $object->getEmail(),
			'comments'     => $hasComments ? array( 'comment' => $comments ) : array(),

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
		if ( $format == "xml" && $data instanceof Payer ) {
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

		$payer = new Payer();
		$payer->addType( $data['@type'] )
		      ->addRef( $data['@ref'] )
		      ->addTitle( $data['title'] )
		      ->addFirstName( $data['firstname'] )
		      ->addSurname( $data['surname'] )
		      ->addCompany( $data['company'] )
		      ->addAddress( $this->denormaliseAddress( $data ) )
		      ->addEmail( $data['email'] );

		$payer->setPhoneNumbers( $this->denormalisePhoneNumbers( $data ) );
		$payer->setComments( $this->denormaliseComments( $data ) );

		return $payer;
	}

	private function denormaliseAddress( $data ) {
		return $this->serializer->denormalize( $data['address'], PayerAddress::GetClassName(), $this->format, $this->context );
	}

	private function denormalisePhoneNumbers( $data ) {
		return $this->serializer->denormalize( $data['phonenumbers'], PhoneNumbers::GetClassName(), $this->format, $this->context );
	}

	private function denormaliseComments( \ArrayAccess $array ) {
		$comments = $array['comments'];

		if ( ! isset( $comments ) ) {
			return null;
		}

		$comments = new SafeArrayAccess( $comments );

		$comments = $comments['comment'];

		if ( ! isset( $comments ) ) {
			return null;
		}

		$commentCollection = new CommentCollection();
		foreach ( $comments as $comment ) {
			$commentObject = new Comment();
			$commentObject->addId( $comment["@id"] )
			              ->addComment( $comment["#"] );

			$commentCollection->add( $commentObject );
		}

		return $commentCollection;
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
		if ( $format == "xml" && $type == Payer::GetClassName() ) {
			return true;
		}

		return false;
	}
}