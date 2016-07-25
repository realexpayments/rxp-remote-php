<?php


namespace com\realexpayments\remote\sdk\domain\payment\normaliser;


use com\realexpayments\remote\sdk\domain\DccInfoResult;
use com\realexpayments\remote\sdk\domain\payment\CardIssuer;
use com\realexpayments\remote\sdk\domain\payment\FraudFilter;
use com\realexpayments\remote\sdk\domain\payment\FraudFilterRule;
use com\realexpayments\remote\sdk\domain\payment\FraudFilterRuleCollection;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\payment\TssResult;
use com\realexpayments\remote\sdk\domain\payment\TssResultCheck;
use com\realexpayments\remote\sdk\SafeArrayAccess;
use com\realexpayments\remote\sdk\utils\NormaliserHelper;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PaymentResponseNormalizer extends AbstractNormalizer {

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
        $response = new PaymentResponse();
        $array    = new SafeArrayAccess( $data );

        $response->setTimeStamp( $array['@timestamp'] );
        $response->setMerchantId( $array['merchantid'] );
        $response->setAccount( $array['account'] );
        $response->setOrderId( $array['orderid'] );
        $response->setResult( $array['result'] );
        $response->setAuthCode( $array['authcode'] );
        $response->setMessage( $array['message'] );
        $response->setPaymentsReference( $array['pasref'] );
        $response->setCvnResult( $array['cvnresult'] );
        $response->setTimeTaken( $array['timetaken'] );
        $response->setAuthTimeTaken( $array['authtimetaken'] );
        $response->setAcquirerResponse( $array['acquirerresponse'] );
        $response->setBatchId( $array['batchid'] );
        $response->setHash( $array['sha1hash'] );
        $response->setAvsPostcodeResponse( $array['avspostcoderesponse'] );
        $response->setAvsAddressResponse( $array['avsaddressresponse'] );
        $response->setTssResult( $this->denormaliseTss( $array ) );
        $response->setCardIssuer( $this->denormaliseCardIssuer( $array ) );
        $response->setDccInfoResult(
            $this->serializer->denormalize( $array['dccinfo'], DccInfoResult::GetClassName(), $format, $context )
        );
        $response->setFraudFilter($this->denormaliseFraudFilter($array));


        return $response;
    }

    private function denormaliseCardIssuer( \ArrayAccess $array ) {
        $cardData = $array['cardissuer'];


        if ( ! isset( $cardData ) || ! is_array( $cardData ) ) {
            return null;
        }

        $data = new SafeArrayAccess( $cardData );

        $cardIssuer = new CardIssuer();
        $cardIssuer->setBank( $data['bank'] );
        $cardIssuer->setCountry( $data['country'] );
        $cardIssuer->setCountryCode( $data['countrycode'] );
        $cardIssuer->setRegion( $data['region'] );

        return $cardIssuer;
    }

    private function denormaliseTss( \ArrayAccess $array ) {

        $tssData = $array['tss'];

        if ( ! isset( $tssData ) || ! is_array( $tssData ) ) {
            return null;
        }

        $data = new SafeArrayAccess( $tssData );

        $tss = new TssResult();
        $tss->setResult( $data['result'] );

        $checks    = $data['check'];
        $tssChecks = array();

        if ( ! empty( $checks ) ) {
            // Ensure that $checks is an array of results
            if ( isset( $checks['@id'] ) ) {
                $checks = array( 0 => $checks );
            }

            foreach ( $checks as $check ) {
                $check = new SafeArrayAccess( $check );

                $tssCheck = new TssResultCheck();
                $tssCheck->setId( $check['@id'] );
                $tssCheck->setValue( $check['#'] );

                $tssChecks[] = $tssCheck;
            }
        }

        $tss->setChecks( $tssChecks );

        return $tss;
    }

    private function denormaliseFraudFilter( \ArrayAccess $array )
    {

        $fraudFilterData = $array['fraudresponse'];

        if (!isset($fraudFilterData) || !is_array($fraudFilterData)) {
            return null;
        }

        $data = new SafeArrayAccess($fraudFilterData);

        $ffr = new FraudFilter();
        $ffr->setMode($data['@mode']);
        $ffr->setResult($data['result']);

        $rules = $data['rules'];
        $ffrRules = new FraudFilterRuleCollection();

        if (!empty($rules)) {
            foreach ($rules as $currentRule) {

                // Ensure that $rules is an array of results
                if (isset($currentRule['@id'])) {
                    $currentRule = array(0 => $currentRule);
                }

                foreach ($currentRule as $ffrRule) {
                    $ffrRule = new SafeArrayAccess($ffrRule);

                    $tmpRule = new FraudFilterRule();
                    $tmpRule->setId($ffrRule['@id']);
                    $tmpRule->setName($ffrRule['@name']);
                    $tmpRule->setAction($ffrRule['action']);

                    $ffrRules->add($tmpRule);
                }

            }


            $ffr->setRules($ffrRules);
        }
        return $ffr;
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
        if ( $format == "xml" && $type == PaymentResponse::GetClassName() ) {
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
        /** @var PaymentResponse $object */

        return array_filter(
            array(
                '@timestamp'          => $object->getTimestamp(),
                'merchantid'          => $object->getMerchantId(),
                'account'             => $object->getAccount(),
                'orderid'             => $object->getOrderId(),
                'result'              => $object->getResult(),
                'authcode'            => $object->getAuthCode(),
                'message'             => $object->getMessage(),
                'pasref'              => $object->getPaymentsReference(),
                'cvnresult'           => $object->getCvnResult(),
                'timetaken'           => $object->getTimeTaken(),
                'authtimetaken'       => $object->getAuthTimeTaken(),
                'acquirerresponse'    => $object->getAcquirerResponse(),
                'batchid'             => $object->getBatchId(),
                'cardissuer'          => $this->normaliseCardIssuer( $object ),
                'sha1hash'            => $object->getHash(),
                'tss'                 => $this->normaliseTss( $object ),
                'avspostcoderesponse' => $object->getAvsPostcodeResponse(),
                'avsaddressresponse'  => $object->getAvsAddressResponse(),
                'dccinfo'             => $object->getDccInfoResult(),
                'fraudresponse'                 => $this->normaliseFraudFilter( $object )

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
        if ( $format == "xml" && $data instanceof PaymentResponse ) {
            return true;
        }

        return false;
    }

    private function normaliseCardIssuer( PaymentResponse $response ) {
        $cardIssuer = $response->getCardIssuer();
        if ( is_null( $cardIssuer ) ) {
            return array();
        }

        return array_filter( array(
            'bank'        => $cardIssuer->getBank(),
            'country'     => $cardIssuer->getCountry(),
            'countrycode' => $cardIssuer->getCountryCode(),
            'region'      => $cardIssuer->getRegion()
        ), array( NormaliserHelper::GetClassName(), "filter_data" ) );
    }

    private function normaliseTss( PaymentResponse $response ) {
        $tss = $response->getTssResult();
        if ( is_null( $tss ) || $this->tss_is_empty( $response ) ) {
            return array();
        }

        return array(
            'result' => $tss->getResult(),
            'check'  => $tss->getChecks()
        );
    }

    private function normaliseFraudFilter( PaymentResponse $response ) {
        $ff = $response->getFraudFilter();
        if ( is_null( $ff ) || $this->fraudfilter_is_empty( $response ) ) {
            return array();
        }

        return array(
            '@mode' => $ff->getMode(),
            'result' => $ff->getResult(),
            'rules'  => $ff->getRules()
        );
    }

    private function tss_is_empty( PaymentResponse $response ) {
        return
            $response->getTssResult()->getResult() == null &&
            $response->getTssResult()->getChecks() == null;
    }

    private function fraudfilter_is_empty( PaymentResponse $response ) {
        return
            $response->getFraudFilter()->getResult() == null &&
            $response->getFraudFilter()->getRules() == null;
    }

}
