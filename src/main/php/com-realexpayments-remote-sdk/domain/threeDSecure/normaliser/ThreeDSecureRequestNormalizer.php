<?php


namespace com\realexpayments\remote\sdk\domain\threeDSecure\normaliser;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;
use com\realexpayments\remote\sdk\domain\threeDSecure\ThreeDSecureRequest;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ThreeDSecureRequestNormalizer extends AbstractNormalizer {

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

		$request = new ThreeDSecureRequest();
		$array   = new SafeArrayAccess( $data );

		$request->addTimestamp( $array['@timestamp'] )
		        ->addType( $array['@type'] )
		        ->addMerchantId( $array['merchantid'] )
		        ->addAccount( $array['account'] )
		        ->addOrderId( $array['orderid'] )
		        ->addCard( $this->denormaliseCard( $array ) )
		        ->addHash( $array['sha1hash'] )
		        ->addPares( $array['pares'] );


		$request->setAmount( $this->denormaliseAmount( $array ) );
		$request->setComments( $this->denormaliseComments( $array ) );

		return $request;
	}

	private function denormaliseAmount( \ArrayAccess $array ) {
		$amountData = $array['amount'];

		if ( is_null( $amountData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $amountData );

		$amount = new Amount();
		$amount->addAmount( $data['#'] );
		$amount->addCurrency( $data['@currency'] );

		return $amount;
	}

	private function denormaliseComments( \ArrayAccess $array ) {
		$comments = $array['comments'];

		if ( ! isset( $comments ) ) {
			return null;
		}

		$comments = new SafeArrayAccess( $comments );

		$comments = $comments['comment'];

		if ( ! isset( $comments ) || ! is_array( $comments ) ) {
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

	private function denormaliseCard( \ArrayAccess $array ) {

		$cardData = $array['card'];

		if ( is_null( $cardData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $cardData );

		$card = new Card();
		$card->addNumber( $data['number'] )
		     ->addExpiryDate( $data['expdate'] )
		     ->addCardHolderName( $data['chname'] )
		     ->addType( $data['type'] )
		     ->addIssueNumber( $data['issueno'] );

		$cvn = $this->denormaliseCVN( $data );
		$card->setCvn( $cvn );

		return $card;
	}

	private function denormaliseCVN( \ArrayAccess $array ) {
		$cvnData = $array['cvn'];

		if ( is_null( $cvnData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $cvnData );

		$cvn = new CVN();
		$cvn->addNumber( $data['number'] )
		    ->addPresenceIndicator( $data['presind'] );

		return $cvn;
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
		if ( $format == "xml" && $type == ThreeDSecureRequest::GetClassName() ) {
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
		/** @var ThreeDSecureRequest $object */

		$hasComments = true;
		$comments = $object->getComments();
		if ( is_null( $comments ) || $comments->getSize() == 0 ) {
			$hasComments = false;
		} else {
			$comments = $comments->getComments();
		}


		return array_filter( array(
			'@timestamp' => $object->getTimestamp(),
			'@type'      => $object->getType(),
			'merchantid' => $object->getMerchantId(),
			'account'    => $object->getAccount(),
			'orderid'    => $object->getOrderId(),
			'amount'     => $this->normaliseAmount( $object ),
			'card'       => $this->normaliseCard( $object ),
			'pares'      => $object->getPares(),
			'sha1hash'   => $object->getHash(),
			'comments'    => $hasComments ? array( 'comment' => $comments ) : array()
		) );
	}

	private function normaliseAmount( ThreeDSecureRequest $request ) {
		$amount = $request->getAmount();
		if ( is_null( $amount ) ) {
			return array();
		}

		return array(
			'@currency' => $amount->getCurrency(),
			'#'         => $amount->getAmount()
		);
	}

	private function normaliseCard( ThreeDSecureRequest $request ) {
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

	/**
	 * Checks whether the given class is supported for normalization by this normalizer.
	 *
	 * @param mixed $data Data to normalize.
	 * @param string $format The format being (de-)serialized from or into.
	 *
	 * @return bool
	 */
	public function supportsNormalization( $data, $format = null ) {
		if ( $format == "xml" && $data instanceof ThreeDSecureRequest ) {
			return true;
		}

		return false;
	}
}