<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\DccInfo;
use com\realexpayments\remote\sdk\domain\Payer;
use com\realexpayments\remote\sdk\domain\payment\Address;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;
use com\realexpayments\remote\sdk\domain\payment\FraudFilter;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;
use com\realexpayments\remote\sdk\domain\payment\Mpi;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\Recurring;
use com\realexpayments\remote\sdk\domain\payment\TssInfo;
use com\realexpayments\remote\sdk\domain\PaymentData;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PaymentRequestNormalizer extends AbstractNormalizer{

	private $format;
	private $context;


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

		$this->format  = $format;
		$this->context = $context;

		$request = new PaymentRequest();
		$array   = new SafeArrayAccess( $data );

		$request->addTimestamp( $array['@timestamp'] )
		        ->addType( $array['@type'] )
		        ->addMerchantId( $array['merchantid'] )
		        ->addAccount( $array['account'] )
		        ->addChannel( $array['channel'] )
		        ->addOrderId( $array['orderid'] )
		        ->addHash( $array['sha1hash'] )
		        ->addPaymentsReference( $array['pasref'] )
		        ->addAuthCode( $array['authcode'] )
		        ->addRefundHash( $array['refundhash'] )
		        ->addMobile( $array['mobile'] )
		        ->addToken( $array['token'] )
		        ->addPayerReference( $array['payerref'] )
		        ->addPaymentMethod( $array['paymentmethod'] )
		        ->addPaymentData( $this->denormalizePaymentData( $array ) )
		        ->addPayer( $this->denormalizePayer( $array ) )
				->addReasonCode($array['reasoncode']);


		$request->setCard( $this->denormaliseCard( $array ) );


		$autoSettle = $this->denormaliseAutoSettle( $array );
		if ( $autoSettle != null ) {
			$request->addAutoSettle( $autoSettle );
		}

		$fraudFilter = $this->denormaliseFraudFilter( $array );
		if ( $fraudFilter != null ) {
			$request->addFraudFilter( $fraudFilter );
		}


		$recurring = $this->denormaliseRecurring( $array );
		if ( $recurring != null ) {
			$request->addRecurring( $recurring );
		}

		$tssInfo = $this->denormaliseTssInfo( $array );
		if ( $tssInfo != null ) {
			$request->addTssInfo( $tssInfo );
		}

		$mpi = $this->denormaliseMpi( $array );
		if ( $mpi != null ) {
			$request->addMpi( $mpi );
		}

		$dccInfo = $this->denormaliseDccInfo($array);
		if ( $dccInfo != null ) {
			$request->addDccInfo( $dccInfo );
		}

		$request->setAmount( $this->denormaliseAmount( $array ) );
		$request->setComments( $this->denormaliseComments( $array ) );

		return $request;
	}

	private function denormaliseAmount( \ArrayAccess $array ) {
		return $this->serializer->denormalize( $array['amount'], Amount::GetClassName(), $this->format, $this->context );
	}

	private function denormaliseComments( \ArrayAccess $array ) {
		$comments = $array['comments'];

		if ( ! isset( $comments ) || !is_array($comments)) {
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


	private function denormaliseAutoSettle( \ArrayAccess $array ) {

		return $this->serializer->denormalize( $array['autosettle'], AutoSettle::GetClassName(), $this->format, $this->context );
	}

	private function denormaliseFraudFilter( \ArrayAccess $array ) {

		return $this->serializer->denormalize( $array['fraudfilter'], FraudFilter::GetClassName(), $this->format, $this->context );
	}

	private function denormaliseRecurring( \ArrayAccess $array ) {

		$recurringData = $array['recurring'];

		if ( is_null( $recurringData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $recurringData );

		$recurring = new Recurring();
		$recurring->addFlag( $data['@flag'] )
		          ->addSequence( $data['@sequence'] )
		          ->addType( $data['@type'] );

		return $recurring;
	}

	private function denormaliseTssInfo( \ArrayAccess $array ) {

		$tssInfoData = $array['tssinfo'];

		if ( is_null( $tssInfoData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $tssInfoData );

		$tssInfo = new TssInfo();
		$tssInfo->addCustomerNumber( $data['custnum'] )
		        ->addProductId( $data['prodid'] )
		        ->addVariableReference( $data['varref'] )
		        ->addCustomerIpAddress( $data['custipaddress'] );

		// add address
		$tssInfo->setAddresses( $this->denormaliseAddresses( $data ) );

		return $tssInfo;

	}

	private function denormaliseAddresses( \ArrayAccess $array ) {

		$addressData = $array['address'];

		if ( is_null( $addressData ) ) {
			return null;
		}

		$addresses = array();

		foreach ( $addressData as $address ) {
			$address       = new SafeArrayAccess( $address );
			$addressObject = new Address();
			$addressObject->addType( $address['@type'] )
			              ->addCode( $address['code'] )
			              ->addCountry( $address['country'] );

			$addresses[] = $addressObject;
		}

		return $addresses;
	}

	private function denormaliseMpi( \ArrayAccess $array ) {

		$mpiData = $array['mpi'];

		if ( is_null( $mpiData ) ) {
			return null;
		}

		$data = new SafeArrayAccess( $mpiData );

		$mpi = new Mpi();
		$mpi->addCavv( $data['cavv'] )
		    ->addXid( $data['xid'] )
		    ->addEci( $data['eci'] );

		return $mpi;
	}

	private function denormalizePaymentData( $array ) {
		return $this->serializer->denormalize( $array['paymentdata'], PaymentData::GetClassName(), $this->format, $this->context );
	}

	private function denormalizePayer( $array ) {
		return $this->serializer->denormalize( $array['payer'], Payer::GetClassName(), $this->format, $this->context );
	}

	private function denormaliseCard( $array ) {
		return $this->serializer->denormalize( $array['card'], Card::GetClassName(), $this->format, $this->context );
	}

	private function denormaliseDccInfo( $array ) {
		return $this->serializer->denormalize( $array['dccinfo'], DccInfo::GetClassName(), $this->format, $this->context );
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
		if ( $format == "xml" && $type == PaymentRequest::GetClassName() ) {
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

		$hasComments = true;
		$comments    = $object->getComments();
		if ( is_null( $comments ) || $comments->getSize() == 0 ) {
			$hasComments = false;
		} else {
			$comments = $comments->getComments();
		}


		return
			array_filter(
				array(
					'@timestamp'    => $object->getTimestamp(),
					'@type'         => $object->getType(),
					'merchantid'    => $object->getMerchantId(),
					'account'       => $object->getAccount(),
					'channel'       => $object->getChannel(),
					'orderid'       => $object->getOrderId(),
					'amount'        => $object->getAmount(),
					'card'          => $object->getCard(),
					'autosettle'    => $object->getAutoSettle(),
					'sha1hash'      => $object->getHash(),
					'comments'      => $hasComments ? array( 'comment' => $comments ) : array(),
					'pasref'        => $object->getPaymentsReference(),
					'authcode'      => $object->getAuthCode(),
					'refundhash'    => $object->getRefundHash(),
					'fraudfilter'   => $object->getFraudFilter(),
					'recurring'     => $this->normaliseRecurring( $object ),
					'tssinfo'       => $this->normaliseTssInfo( $object ),
					'mpi'           => $this->normaliseMpi( $object ),
					'mobile'        => $object->getMobile(),
					'token'         => $object->getToken(),
					'payerref'      => $object->getPayerRef(),
					'paymentmethod' => $object->getPaymentMethod(),
					'paymentdata'   => $object->getPaymentData(),
					'payer'         => $object->getPayer(),
					'dccinfo'       => $object->getDccInfo(),
					'reasoncode'       => $object->getReasonCode(),
				), array( NormaliserHelper::GetClassName(), "filter_data" ) );
	}

	private function normaliseRecurring( PaymentRequest $request ) {

		$recurring = $request->getRecurring();
		if ( is_null( $recurring ) || $this->recurring_is_empty( $request ) ) {
			return array();
		}

		return array_filter( array(
			'@flag'     => $recurring->getFlag(),
			'@sequence' => $recurring->getSequence(),
			'@type'     => $recurring->getType(),
		), array( NormaliserHelper::GetClassName(), "filter_data" ) );
	}

	private function recurring_is_empty( PaymentRequest $request ) {
		return $request->getRecurring()->getFlag() == null &&
		       $request->getRecurring()->getSequence() == null &&
		       $request->getRecurring()->getType() == null;
	}

	private function normaliseTssInfo( PaymentRequest $request ) {
		$tssInfo = $request->getTssInfo();
		if ( is_null( $tssInfo ) || $this->tss_is_empty( $request ) ) {
			return array();
		}

		return array_filter( array(
			'custnum'       => $tssInfo->getCustomerNumber(),
			'prodid'        => $tssInfo->getProductId(),
			'varref'        => $tssInfo->getVariableReference(),
			'custipaddress' => $tssInfo->getCustomerIpAddress(),
			'address'       => $tssInfo->getAddresses()
		), array( NormaliserHelper::GetClassName(), "filter_data" ) );
	}

	private function tss_is_empty( PaymentRequest $request ) {
		return $request->getTssInfo()->getCustomerNumber() == null &&
		       $request->getTssInfo()->getProductId() == null &&
		       $request->getTssInfo()->getVariableReference() == null &&
		       $request->getTssInfo()->getCustomerIpAddress() == null &&
		       $request->getTssInfo()->getAddresses() == null;
	}

	private function normaliseMpi( PaymentRequest $request ) {
		$mpi = $request->getMpi();
		if ( is_null( $mpi ) || $this->mpi_is_empty( $request ) ) {
			return array();
		}

		return array_filter( array(
			'cavv' => $mpi->getCavv(),
			'xid'  => $mpi->getXid(),
			'eci'  => $mpi->getEci()
		), array( NormaliserHelper::GetClassName(), "filter_data" ) );
	}

	private function mpi_is_empty( PaymentRequest $request ) {
		return $request->getMpi()->getCavv() == null &&
		       $request->getMpi()->getXid() == null &&
		       $request->getMpi()->getEci() == null;
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


}