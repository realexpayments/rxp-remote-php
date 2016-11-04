<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\DccInfo;
use com\realexpayments\remote\sdk\domain\iRequest;
use com\realexpayments\remote\sdk\domain\Payer;
use com\realexpayments\remote\sdk\domain\PaymentData;
use com\realexpayments\remote\sdk\utils\GenerationUtils;
use com\realexpayments\remote\sdk\utils\MessageType;
use com\realexpayments\remote\sdk\utils\XmlUtils;


/**
 * Class PaymentRequest
 *
 ** <p>
 * Class representing a Payment request to be sent to Realex.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example AUTH:
 * </p>
 * <p><code><pre>
 * $card = (new Card())
 *      ->addType(CardType::VISA)
 *      ->addNumber("4242424242424242")
 *      ->addExpiryDate("0525")
 *    	->addCvn("123")
 *    	->addCvnPresenceIndicator(PresenceIndicator::CVN_PRESENT);
 *    	->addCardHolderName("Joe Bloggs");
 *
 * $request = (new PaymentRequest())
 *      ->addMerchantId("yourMerchantId")
 *      ->addAccount("yourAccount")
 *      ->addType(PaymentType::AUTH)
 *      ->addAmount(10001)
 *      ->addCurrency("EUR")
 *      ->addCard($card)
 *      ->addAutoSettle((new AutoSettle())->addFlag(AutoSettleFlag::TRUE));
 * </pre></code></p>
 *
 * <p>
 * Example AUTH with Address Verification:
 * <p>
 * <p><code><pre>
 * $card = (new Card())
 *      ->addType(CardType::VISA)
 *      ->addNumber("4242424242424242")
 *      ->addExpiryDate("0525")
 *    ->addCvn("123")
 *    ->addCvnPresenceIndicator(PresenceIndicator::CVN_PRESENT)
 *    ->addCardHolderName("Joe Bloggs");
 *
 * $request = (new PaymentRequest())
 *      ->addMerchantId("yourMerchantId")
 *      ->addAccount("yourAccount")
 *      ->addType(PaymentType::AUTH)
 *      ->addAmount(10001)
 *      ->addCurrency("EUR")
 *      ->addCard($card)
 *      ->addAutoSettle((new AutoSettle())->addFlag(AutoSettleFlag::TRUE));
 *    ->addAddressVerificationServiceDetails("382 The Road", "WB1 A42", "GB");
 * </pre></code></p>
 *
 * <p>
 * Example AUTH MOBILE
 * <p>
 * <p><code><pre>
 * $request = (new PaymentRequest())
 *      ->addMerchantId("yourMerchantId")
 *      ->addAccount("yourAccount")
 *    ->addType(PaymentType::AUTH_MOBILE)
 *      ->addAutoSettle((new AutoSettle())->addFlag(AutoSettleFlag::TRUE));
 *      ->addMobile("apple-pay")
 *      ->addToken("{auth mobile payment token}");
 * </pre></code></p>
 *
 *
 * <p>
 * Example SETTLE
 * <p>
 * <p><code><pre>
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::SETTLE )
 *    ->addOrderId("Order ID from original transaction")
 *    ->addAmount( 1001 )
 *    ->addCurrency( "EUR" )
 *    ->addPaymentsReference("pasref from original transaction")
 *    ->addAuthCode("Auth code from original transaction");
 * </pre></code></p>
 *
 * <p>
 * Example Void
 * <p>
 * <p><code><pre>
 *
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::VOID )
 *    ->addOrderId("Order ID from original transaction")
 *    ->addPaymentsReference("pasref from original transaction")
 *    ->addAuthCode("Auth code from original transaction");
 *
 * </pre></code></p>
 *
 * <p>
 * Example REBATE
 * <p>
 * <p><code><pre>
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::REBATE )
 *    ->addOrderId("Order ID from original transaction")
 *    ->addAmount( 1001 )
 *    ->addCurrency( "EUR" )
 *    ->addPaymentsReference("pasref from original transaction")
 *    ->addAuthCode("Auth code from original transaction")
 *    ->addRefundHash("Hash of rebate password shared with Realex");
 *
 * </pre></code></p>
 *
 * <p>
 * Example OTB
 * <p>
 * <p><code><pre>
 *
 * $card = ( new Card() )
 *    ->addExpiryDate( "1220" )
 *    ->addNumber( "4263971921001307" )
 *    ->addType( CardType::VISA )
 *    ->addCardHolderName( "Joe Smith" );
 *    ->addCvn( "123" )
 *    ->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
 *
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::OTB )
 *    ->addCard( $card );
 *
 * </pre></code></p>
 *
 * <p>
 * Example Credit
 * <p>
 * <p><code><pre>
 *
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::REFUND )
 *    ->addAmount( 1001 )
 *    ->addCurrency( "EUR" )
 *    ->addPaymentsReference("Pasref from original transaction")
 *    ->addAuthCode("Auth code from original transaction")
 *    ->addRefundHash("Hash of credit password shared with Realex");
 *
 * </pre></code></p>
 *
 * <p>
 * Example Hold
 * <p>
 * <p><code><pre>
 *
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::HOLD )
 *    ->addOrderId("Order ID from original transaction")
 *    ->addReasonCode( ReasonCode::FRAUD)
 *    ->addPaymentsReference("Pasref from original transaction");
 *
 * </pre></code></p>
 *
 * <p>
 * Example Release
 * <p>
 * <p><code><pre>
 *
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType( PaymentType::RELEASE )
 *    ->addOrderId("Order ID from original transaction")
 *    ->addReasonCode( ReasonCode::FRAUD)
 *    ->addPaymentsReference("Pasref from original transaction");
 *
 * </pre></code></p>
 * <p>
 * Example Receipt-in:
 * </p>
 * <p><code><pre>
 * $paymentData = ( new PaymentData())
 *    ->addCvnNumber("123");
 * <p/>
 * $request =(  new PaymentRequest() )
 *    ->addAccount("yourAccount")
 *    ->addMerchantId("yourMerchantId")
 *    ->addType(PaymentType::RECEIPT_IN)
 *    ->addOrderId("Order ID from original transaction")
 *    ->addAmount(100)
 *    ->addCurrency("EUR")
 *    ->addPayerReference("payer ref from customer")
 *    ->addPaymentMethod("payment method ref from customer")
 *    ->addPaymentData($paymentData);
 * </pre></code></p>
 * <p/>
 * <p>
 * Example Payment-out:
 * </p>
 * <p><code><pre>
 * $request =(  new PaymentRequest() )
 *  ->addAccount("yourAccount")
 *  ->addMerchantId("yourMerchantId")
 *  ->addType(PaymentType::PAYMENT_OUT)
 *  ->addAmount(100)
 *  ->addCurrency("EUR")
 *  ->addPayerReference("payer ref from customer")
 *  ->addPaymentMethod("payment method ref from customer")
 *  ->addRefundHash("Hash of rebate password shared with Realex");
 * <p/>
 *  $client = new RealexClient("shared secret");
 *  $response = client->send(request);
 * </pre></code></p>
 * <p/>
 * <p>
 * Example Payer-new:
 * </p>
 * <p><code><pre>
 * <p/>
 * $address = ( new PayerAddress() )
 * ->addLine1("Apt 167 Block 10")
 * ->addLine2("The Hills")
 * ->addLine3("67-69 High St")
 * ->addCity("Hytown")
 * ->addCounty("Dunham")
 * ->addPostCode("3")
 * ->addCountryCode("IE")
 * ->addCountryName("Ireland");
 * <p/>
 * $payer = ( new Payer() )
 * ->addType("Business")
 * ->addRef("smithj01")
 * ->addTitle("Mr")
 * ->addFirstName("John")
 * ->addSurname("Smith")
 * ->addCompany("Acme")
 * ->addAddress($address)
 * ->addHomePhoneNumber("+35317285355")
 * ->addWorkPhoneNumber("+35317433923")
 * ->addFaxPhoneNumber("+35317893248")
 * ->addMobilePhoneNumber("+353873748392")
 * ->addEmail("jsmith@acme->com")
 * ->addComment("Comment1")
 * ->addComment("Comment2");
 * <p/>
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::PAYER_NEW)
 * ->addPayer(payer);
 * <p/>
 * </pre></code></p>
 * <p/>
 * <p>
 * Example Payer-edit:
 * </p>
 * <p><code><pre>
 * <p/>
 * $address = ( new PayerAddress() )
 * ->addLine1("Apt 167 Block 10")
 * ->addLine2("The Hills")
 * ->addLine3("67-69 High St")
 * ->addCity("Hytown")
 * ->addCounty("Dunham")
 * ->addPostCode("3")
 * ->addCountryCode("IE")
 * ->addCountryName("Ireland");
 * <p/>
 * $payer = ( new Payer() )
 * ->addType("Business")
 * ->addRef("smithj01")
 * ->addTitle("Mr")
 * ->addFirstName("John")
 * ->addSurname("Smith")
 * ->addCompany("Acme")
 * ->addAddress($address)
 * ->addHomePhoneNumber("+35317285355")
 * ->addWorkPhoneNumber("+35317433923")
 * ->addFaxPhoneNumber("+35317893248")
 * ->addMobilePhoneNumber("+353873748392")
 * ->addEmail("jsmith@acme->com")
 * ->addComment("Comment1")
 * ->addComment("Comment2");
 * <p/>
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::PAYER_EDIT)
 * ->addPayer(payer);
 * <p/>
 * </pre></code></p>
 * <p/>
 * <p>
 * Example card add:
 * </p>
 * <p><code><pre>
 * <p/>
 * $card = ( new Card() )
 * ->addReference("visa01")
 * ->addPayerReference("smithj01")
 * ->addNumber("420000000000000000")
 * ->addExpiryDate("0119")
 * ->addCardHolderName("Joe Smith")
 * ->addType(CardType::VISA)
 * ->addIssueNumber("1");
 * <p/>
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::CARD_NEW)
 * ->addPayerReference( "smithj01" )
 * ->addCard($card);
 * <p/>
 * </pre></code></p>
 * <p/>
 * <p>
 * Example card update:
 * </p>
 * <p><code><pre>
 * <p/>
 * $card = ( new Card() )
 * ->addReference("visa01")
 * ->addPayerReference("smithj01")
 * ->addNumber("420000000000000000")
 * ->addExpiryDate("0119")
 * ->addCardHolderName("Joe Smith")
 * ->addType(CardType::VISA)
 * ->addIssueNumber("1");
 * <p/>
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::CARD_UPDATE)
 * ->addPayerReference( "smithj01" )
 * ->addCard($card);
 * <p/>
 * </pre></code></p>
 * <p/>
 * <p>
 * Example card delete:
 * </p>
 * <p><code><pre>
 * <p/>
 * $card = ( new Card() )
 * ->addReference("visa01")
 * ->addPayerReference("smithj01");
 *
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::CARD_CANCEL)
 * ->addCard($card);
 * <p/>
 * </pre></code></p>
 * <p/>
 *
 * <p>
 * Example dcc rate lookup:
 * </p>
 * <p><code><pre>
 * <p/>
 * $card = ( new Card() )
 * ->addNumber("420000000000000000")
 * ->addExpiryDate("0119")
 * ->addCardHolderName("Joe Smith")
 * ->addType(CardType::VISA);
 *
 * $dccInfo = ( new DccInfo() )
 * ->addDccProcessor("fexco");
 *
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::DCC_RATE_LOOKUP)
 * ->addAmount(100)
 * ->addCurrency("EUR")
 * ->addCard($card)
 * ->addDccInfo($dccInfo);
 * <p/>
 * </pre></code></p>
 *
 * <p>
 * Example dcc auth:
 * </p>
 * <p><code><pre>
 * <p/>
 *
 * $dccInfo = ( new DccInfo() )
 * ->addDccProcessor("fexco")
 * ->addRate(0->6868)
 * ->addAmount(13049)
 * ->addCurrency("GBP");
 *
 * $card = ( new Card() )
 * ->addNumber("420000000000000000")
 * ->addExpiryDate("0119")
 * ->addCardHolderName("Joe Smith")
 * ->addType(CardType::VISA);
 *
 * $request =(  new PaymentRequest() )
 * ->addAccount("yourAccount")
 * ->addMerchantId("yourMerchantId")
 * ->addType(PaymentType::DCC_RATE_LOOKUP)
 * ->addAmount(100)
 * ->addCurrency("EUR")
 * ->addCard($card)
 * ->addDccInfo($dccInfo);
 * <p/>
 *
 * <p>
 * Example Receipt-in OTB:
 * </p>
 * <p><code><pre>
 * $paymentData = ( new PaymentData())
 *    ->addCvnNumber("123");
 *
 * $request =(  new PaymentRequest() )
 *    ->addAccount("yourAccount")
 *    ->addMerchantId("yourMerchantId")
 *    ->addType(PaymentType::RECEIPT_IN_OTB)
 *    ->addOrderId("Order ID from original transaction")
 *    ->addAmount(100)
 *    ->addCurrency("EUR")
 *    ->addPayerReference("payer ref from customer")
 *    ->addPaymentMethod("payment method ref from customer")
 *    ->addPaymentData($paymentData);
 * </pre></code></p>
 *
 * <p>
 * Example Stored Card Dcc Rate:
 * </p>
 * <p><code><pre>
 * $card = ( new Card() )
 *    ->addNumber("420000000000000000")
 *    ->addExpiryDate("0119")
 *    ->addCardHolderName("Joe Smith")
 *    ->addType(CardType::VISA);
 *
 * $dccInfo = ( new DccInfo() )
 *    ->addDccProcessor("fexco")
 *    ->addRate(0.6868)
 *    ->addAmount(13049)
 *    ->addCurrency("GBP");
 *
 * $request = ( new PaymentRequest() )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addType(PaymentType::STORED_CARD_DCC_RATE)
 *    ->addAmount(19000)
 *    ->addCurrency( "EUR" )
 *    ->addCard($card)
 *    ->addDccInfo($dccInfo);
 * </pre></code></p>
 *
 * <p>
 * Example Fraud Filter Request:
 * </p>
 * <p><code><pre>
 * $fraudFilter = new FraudFilter();
 * $fraudFilter->addMode(FraudFilterMode::ACTIVE);
 *
 * $card = ( new Card() )
 *    ->addNumber("420000000000000000")
 *    ->addExpiryDate("0119")
 *    ->addCardHolderName("Joe Smith")
 *    ->addType(CardType::VISA);
 *
 * $autoSettle = new AutoSettle();
 * $autoSettle->addFlag(AutoSettleFlag::TRUE);
 *
 * $request = ( new PaymentRequest() )
 *    ->addType( PaymentType::AUTH )
 *    ->addCard( $card )
 *    ->addAccount( "myAccount" )
 *    ->addMerchantId( "myMerchantId" )
 *    ->addAmount( 1000 )
 *    ->addCurrency( "EUR" )
 *    ->addOrderId("myOrderId")
 *    ->addAutoSettle( $autoSettle)
 *    ->addFraudFilter($fraudFilter);
 * </pre></code></p>
 *
 * <p>
 * Example Fraud Filter Response:
 * </p>
 * <p><code><pre>
 * $response = $client->send( $request );
 *
 * $mode = $response->getFraudFilter()->getMode();
 * $result = $response->getFraudFilter()->getResult();
 * //array of FraudFilterResultRule
 * $rules = $response->getFraudFilter()->getRules();
 * foreach($rules->getRules() as $rule)
 * {
 *    echo $rule->getId();
 *    echo $rule->getName();
 *    echo $rule->getAction();
 * }
 * //or
 * echo $rules->get(0)->getId();
 * </pre></code></p>

 * @author vicpada
 * @package com\realexpayments\remote\sdk\domain\payment
 */
class PaymentRequest implements iRequest {


	/**
	 * @var string Format of timestamp is yyyyMMddhhmmss  e.g. 20150131094559 for 31/01/2015 09:45:59.
	 * If the timestamp is more than a day (86400 seconds) away from the server time, then the request is rejected.
	 *
	 */
	private $timestamp;

	/**
	 * @var string The payment type
	 *
	 */
	private $type;

	/**
	 * @var string Represents Realex Payments assigned merchant id.
	 *
	 */
	private $merchantId;

	/**
	 * @var string Represents the Realex Payments subaccount to use. If this element is omitted, then the
	 * default account is used.
	 *
	 */
	private $account;

	/**
	 * @var string For certain acquirers it is possible to specify whether a transaction is to be processed as a
	 * Mail Order/Telephone Order or Ecommerce transaction. For other banks, this is configured on the
	 * Merchant ID level.
	 *
	 */
	private $channel;

	/**
	 * @var string Represents the unique order id of this transaction. Must be unique across all sub-accounts.
	 *
	 */
	private $orderId;

	/**
	 * @var Amount Object containing the amount value and the currency type.
	 *
	 */
	private $amount;

	/**
	 * @var Card Object containing the card details to be passed in request.
	 *
	 */
	private $card;

	/**
	 * @var AutoSettle Object containing the auto settle flag.
	 *
	 */
	private $autoSettle;

	/**
	 * @var string Hash constructed from the time stamp, merchand ID, order ID, amount, currency, card number
	 * and secret values.
	 *
	 */
	private $hash;

	/**
	 * @var CommentCollection List of {@link Comment} objects to be passed in request. Optionally, up to two comments
	 * can be associated with any transaction.
	 *
	 */
	private $comments;

	/**
	 * <p>
	 * Represents the Realex Payments reference of the original transaction (this is included in the
	 * response to the auth).
	 * </p>
	 * <p>
	 * Used in requests where the original transaction must be referenced e.g. a REBATE request.
	 * </p>
	 *
	 * @var string payment reference
	 *
	 */
	private $paymentsReference;

	/**
	 * <p>
	 * Represents the authcode of the original transaction, which was included in the response.
	 * </p>
	 * <p>
	 * Used in requests where the original transaction must be referenced e.g. a REBATE request.
	 * </p>
	 *
	 * @var string auth code
	 *
	 */
	private $authCode;

	/**
	 * @var string Represents a hash of the refund password, which Realex Payments will provide. The SHA1
	 * algorithm must be used to generate this hash.
	 *
	 */
	private $refundHash;

	/**
	 * @var FraudFilter Object Fraud filter mode
	 *
	 */
	private $fraudFilter;

	/**
	 * @var Recurring If you are configured for recurring/continuous authority transactions, you must set the recurring values.
	 *
	 */
	private $recurring;

	/**
	 * @var TssInfo Contains optional variables which can be used to identify customers in the
	 * Realex Payments system.
	 *
	 */
	private $tssInfo;

	/**
	 * @var Mpi Contains 3D Secure/Secure Code information if this transaction has used a 3D
	 * Secure/Secure Code system, either Realex's RealMPI or a third party's.
	 *
	 */
	private $mpi;

	/**
	 * @var string The mobile auth payment type e.g. apple-pay.
	 *
	 */
	private $mobile;

	/**
	 * @var string The mobile auth payment token to be sent in place of payment data.
	 *
	 */
	private $token;

	/**
	 * @var string The payer reference for this customer
	 */
	private $payerRef;

	/**
	 * @var string The payment reference
	 */
	private $paymentMethod;

	/**
	 * @var PaymentData Contains payment information to be used on Receipt-in transactions
	 */
	private $paymentData;

	/**
	 * @var Payer {@link Payer} information to be used on Card Storage transactions
	 */
	private $payer;

	/**
	 * @var DccInfo {@link DccInfo} information to be used DCC Rate look up transactions
	 */
	private $dccInfo;

	/**
	 * @var string This is a code used to identify the reason
	 * for a transaction action. It is an optional
	 * field but if populated it must contain a
	 * value that is allowed for that transaction type.
	 * If no value is supplied, the default reason
	 * code NOTGIVEN will be applied to the holdrequest
	 */
	private $reasonCode;


	/**
	 * Constructor for Payment Request
	 */
	public function __construct() {

	}

	public static function GetClassName() {
		return __CLASS__;
	}


	/**
	 * Getter for TSS info
	 *
	 * @return TssInfo
	 */
	public function getTssInfo() {
		return $this->tssInfo;
	}

	/**
	 * Setter for TSS info
	 *
	 * @param TssInfo $tssInfo
	 */
	public function setTssInfo( TssInfo $tssInfo ) {
		$this->tssInfo = $tssInfo;
	}


	/**
	 * Getter for timestamp
	 *
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * Setter for timestamp
	 *
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * Getter for type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Setter for type
	 *
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter for merchantId
	 *
	 * @return string
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Setter for merchantId
	 *
	 * @param string $merchantId
	 */
	public function setMerchantId( $merchantId ) {
		$this->merchantId = $merchantId;
	}

	/**
	 * Getter for account
	 *
	 * @return string
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * Setter for account
	 *
	 * @param string $account
	 */
	public function setAccount( $account ) {
		$this->account = $account;
	}

	/**
	 * Getter for channel
	 *
	 * @return string
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * Setter for channel
	 *
	 * @param string $channel
	 */
	public function setChannel( $channel ) {
		$this->channel = $channel;
	}

	/**
	 * Getter for orderId
	 *
	 * @return string
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * Setter for orderId
	 *
	 * @param string $orderId
	 */
	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	/**
	 * Getter for amount
	 *
	 * @return Amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Setter for amount
	 *
	 * @param Amount $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	/**
	 * Getter for card
	 *
	 * @return Card
	 */
	public function getCard() {
		return $this->card;
	}

	/**
	 * Setter for card
	 *
	 * @param Card $card
	 */
	public function setCard( $card ) {
		$this->card = $card;
	}

	/**
	 * Getter for autoSettle
	 *
	 * @return AutoSettle
	 */
	public function getAutoSettle() {
		return $this->autoSettle;
	}

	/**
	 * Setter for autoSettle
	 *
	 * @param AutoSettle $autoSettle
	 */
	public function setAutoSettle( $autoSettle ) {
		$this->autoSettle = $autoSettle;
	}

	/**
	 * Getter for hash
	 *
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Setter for hash
	 *
	 * @param string $hash
	 */
	public function setHash( $hash ) {
		$this->hash = $hash;
	}

	/**
	 * Getter for comments
	 *
	 * @return CommentCollection
	 *
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Setter for comments
	 *
	 * @param CommentCollection $comments
	 */
	public function setComments( $comments ) {
		$this->comments = $comments;
	}

	/**
	 * Getter for paymentsReference
	 *
	 * @return string
	 */
	public function getPaymentsReference() {
		return $this->paymentsReference;
	}

	/**
	 * Setter for paymentsReference
	 *
	 * @param string $paymentsReference
	 */
	public function setPaymentsReference( $paymentsReference ) {
		$this->paymentsReference = $paymentsReference;
	}

	/**
	 * Getter for refundHash
	 *
	 * @return string
	 */
	public function getRefundHash() {
		return $this->refundHash;
	}

	/**
	 * Setter for refundHash
	 *
	 * @param string $refundHash
	 */
	public function setRefundHash( $refundHash ) {
		$this->refundHash = $refundHash;
	}

	/**
	 * Getter for authCode
	 *
	 * @return string
	 */
	public function getAuthCode() {
		return $this->authCode;
	}

	/**
	 * Setter for authCode
	 *
	 * @param string $authCode
	 */
	public function setAuthCode( $authCode ) {
		$this->authCode = $authCode;
	}

	/**
	 * Getter for fraudFilter
	 *
	 * @return string
	 */
	public function getFraudFilter() {
		return $this->fraudFilter;
	}

	/**
	 * Setter for fraudFilter
	 *
	 * @param string $fraudFilter
	 */
	public function setFraudFilter( $fraudFilter ) {
		$this->fraudFilter = $fraudFilter;
	}

	/**
	 * Getter for recurring
	 *
	 * @return Recurring
	 */
	public function getRecurring() {
		return $this->recurring;
	}

	/**
	 * Setter for recurring
	 *
	 * @param Recurring $recurring
	 */
	public function setRecurring( $recurring ) {
		$this->recurring = $recurring;
	}

	/**
	 * Getter for mpi
	 *
	 * @return Mpi
	 */
	public function getMpi() {
		return $this->mpi;
	}

	/**
	 * Setter for mpi
	 *
	 * @param Mpi $mpi
	 */
	public function setMpi( $mpi ) {
		$this->mpi = $mpi;
	}

	/**
	 * Getter for mobile
	 *
	 * @return string
	 */
	public function getMobile() {
		return $this->mobile;
	}

	/**
	 * Setter for mobile
	 *
	 * @param string $mobile
	 */
	public function setMobile( $mobile ) {
		$this->mobile = $mobile;
	}

	/**
	 * Getter for token
	 *
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * Setter for token
	 *
	 * @param string $token
	 */
	public function setToken( $token ) {
		$this->token = $token;
	}

	/**
	 * Getter for payerRef
	 *
	 * @return string
	 */
	public function getPayerRef() {
		return $this->payerRef;
	}

	/**
	 * Setter for payerRef
	 *
	 * @param string $payerRef
	 */
	public function setPayerRef( $payerRef ) {
		$this->payerRef = $payerRef;
	}

	/**
	 * Getter for paymentMethod
	 *
	 * @return string
	 */
	public function getPaymentMethod() {
		return $this->paymentMethod;
	}

	/**
	 * Setter for paymentMethod
	 *
	 * @param string $paymentMethod
	 */
	public function setPaymentMethod( $paymentMethod ) {
		$this->paymentMethod = $paymentMethod;
	}

	/**
	 * Getter for paymentData
	 *
	 * @return PaymentData
	 */
	public function getPaymentData() {
		return $this->paymentData;
	}

	/**
	 * Setter for paymentData
	 *
	 * @param PaymentData $paymentData
	 */
	public function setPaymentData( $paymentData ) {
		$this->paymentData = $paymentData;
	}

	/**
	 * Getter for payer
	 *
	 * @return Payer
	 */
	public function getPayer() {
		return $this->payer;
	}

	/**
	 * Setter for payer
	 *
	 * @param Payer $payer
	 */
	public function setPayer( $payer ) {
		$this->payer = $payer;
	}

	/**
	 * Getter for dccInfo
	 *
	 * @return DccInfo
	 */
	public function getDccInfo() {
		return $this->dccInfo;
	}

	/**
	 * Setter for dccInfo
	 *
	 * @param DccInfo $dccInfo
	 */
	public function setDccInfo( $dccInfo ) {
		$this->dccInfo = $dccInfo;
	}

	/**
	 * Getter for reason code
	 *
	 * @return string
	 */
	public function getReasonCode() {
		return $this->reasonCode;
	}

	/**
	 * Setter for reason code
	 *
	 * @param string $reasonCode
	 */
	public function setReasonCode( $reasonCode ) {
		$this->reasonCode = $reasonCode;
	}


	/**
	 * Helper method for adding TSS info
	 *
	 * @param TssInfo $tssInfo
	 *
	 * @return $this
	 */
	public function addTssInfo( TssInfo $tssInfo ) {
		$this->tssInfo = $tssInfo;

		return $this;
	}

	/**
	 * Helper method for adding a type
	 *
	 * @param string $type
	 *
	 * @return PaymentRequest
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method for adding a type
	 *
	 * @param PaymentType $type
	 *
	 * @return PaymentRequest
	 */
	public function addPaymentType( PaymentType $type ) {
		$this->type = $type->getType();

		return $this;
	}

	/**
	 * Helper method for adding a merchantId
	 *
	 * @param string $merchantId
	 *
	 * @return PaymentRequest
	 */
	public function addMerchantId( $merchantId ) {
		$this->merchantId = $merchantId;

		return $this;
	}

	/**
	 * Helper method for adding a account
	 *
	 * @param string $account
	 *
	 * @return PaymentRequest
	 */
	public function addAccount( $account ) {
		$this->account = $account;

		return $this;
	}

	/**
	 * Helper method for adding a timestamp
	 *
	 * @param string $timestamp
	 *
	 * @return PaymentRequest
	 */
	public function addTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;

		return $this;
	}

	/**
	 * Helper method for adding a orderId
	 *
	 * @param string $orderId
	 *
	 * @return PaymentRequest
	 */
	public function addOrderId( $orderId ) {
		$this->orderId = $orderId;

		return $this;
	}

	/**
	 * Helper method for adding a amount
	 *
	 * @param int $amount
	 *
	 * @return PaymentRequest
	 */
	public function addAmount( $amount ) {
		if ( is_null( $this->amount ) ) {
			$this->amount = new Amount();
			$this->amount->addAmount( $amount );
		} else {
			$this->amount->addAmount( $amount );
		}

		return $this;
	}

	/**
	 * Helper method for adding a currency
	 *
	 * @param string $currency
	 *
	 * @return PaymentRequest
	 */
	public function addCurrency( $currency ) {
		if ( is_null( $this->amount ) ) {
			$this->amount = new Amount();
			$this->amount->addCurrency( $currency );
		} else {
			$this->amount->addCurrency( $currency );
		}

		return $this;
	}

	/**
	 * Helper method for adding a card
	 *
	 * @param Card $card
	 *
	 * @return PaymentRequest
	 */
	public function addCard( Card $card ) {
		$this->card = $card;

		return $this;
	}

	/**
	 * Helper method for adding a autoSettle
	 *
	 * @param AutoSettle $autoSettle
	 *
	 * @return PaymentRequest
	 */
	public function addAutoSettle( AutoSettle $autoSettle ) {
		$this->autoSettle = $autoSettle;

		return $this;
	}

	/**
	 * Helper method for adding a comment. NB Only 2 comments will be accepted by Realex.
	 *
	 * @param string $comment
	 *
	 * @return PaymentRequest
	 */
	public function addComment( $comment ) {
		//create new comments array list if null
		if ( is_null( $this->comments ) ) {
			$this->comments = new CommentCollection();
		}

		$size          = $this->comments->getSize();
		$commentObject = new Comment();
		$this->comments->add( $commentObject->addComment( $comment )->addId( ++ $size ) );

		return $this;
	}

	/**
	 * Helper method for adding a paymentsReference
	 *
	 * @param string $paymentsReference
	 *
	 * @return PaymentRequest
	 */
	public function addPaymentsReference( $paymentsReference ) {
		$this->paymentsReference = $paymentsReference;

		return $this;
	}

	/**
	 * Helper method for adding a authCode
	 *
	 * @param string $authCode
	 *
	 * @return PaymentRequest
	 */
	public function addAuthCode( $authCode ) {
		$this->authCode = $authCode;

		return $this;
	}

	/**
	 * Helper method for adding a refundHash
	 *
	 * @param string $refundHash
	 *
	 * @return PaymentRequest
	 */
	public function addRefundHash( $refundHash ) {
		$this->refundHash = $refundHash;

		return $this;
	}

	/**
	 * Helper method for adding a hash
	 *
	 * @param string $hash
	 *
	 * @return PaymentRequest
	 */
	public function addHash( $hash ) {
		$this->hash = $hash;

		return $this;
	}

	/**
	 * Helper method for adding a channel
	 *
	 * @param string $channel
	 *
	 * @return PaymentRequest
	 */
	public function addChannel( $channel ) {
		$this->channel = $channel;

		return $this;
	}

	/**
	 * Helper method for adding a fraudFilter
	 *
	 * @param FraudFilter $fraudFilter
	 *
	 * @return PaymentRequest
	 */
	public function addFraudFilter( FraudFilter $fraudFilter ) {
		$this->fraudFilter = $fraudFilter;

		return $this;
	}

	/**
	 * Helper method for adding a recurring
	 *
	 * @param Recurring $recurring
	 *
	 * @return PaymentRequest
	 */
	public function addRecurring( Recurring $recurring ) {
		$this->recurring = $recurring;

		return $this;
	}

	/**
	 * Helper method for adding a mpi
	 *
	 * @param Mpi $mpi
	 *
	 * @return PaymentRequest
	 */
	public function addMpi( Mpi $mpi ) {
		$this->mpi = $mpi;

		return $this;
	}

	/**
	 * Helper method for adding a mobile
	 *
	 * @param string $mobile
	 *
	 * @return PaymentRequest
	 */
	public function addMobile( $mobile ) {
		$this->mobile = $mobile;

		return $this;
	}

	/**
	 * Helper method for adding a token
	 *
	 * @param string $token
	 *
	 * @return PaymentRequest
	 */
	public function addToken( $token ) {
		$this->token = $token;

		return $this;
	}

	/**
	 * Helper method for adding a payerRef
	 *
	 * @param string $payerRef
	 *
	 * @return PaymentRequest
	 */
	public function addPayerReference( $payerRef ) {
		$this->payerRef = $payerRef;

		return $this;
	}

	/**
	 * Helper method for adding a paymentMethod
	 *
	 * @param string $paymentMethod
	 *
	 * @return PaymentRequest
	 */
	public function addPaymentMethod( $paymentMethod ) {
		$this->paymentMethod = $paymentMethod;

		return $this;
	}

	/**
	 * Helper method for adding a paymentData
	 *
	 * @param PaymentData $paymentData
	 *
	 * @return PaymentRequest
	 */
	public function addPaymentData( $paymentData ) {
		$this->paymentData = $paymentData;

		return $this;
	}

	/**
	 * Helper method for adding a payer
	 *
	 * @param Payer $payer
	 *
	 * @return PaymentRequest
	 */
	public function addPayer( $payer ) {
		$this->payer = $payer;

		return $this;
	}

	/**
	 * Helper method for adding a dccInfo
	 *
	 * @param DccInfo $dccInfo
	 *
	 * @return PaymentRequest
	 */
	public function addDccInfo( $dccInfo ) {
		$this->dccInfo = $dccInfo;

		return $this;
	}


	/**
	 * Helper method for adding a reason code
	 *
	 * @param string $reasonCode
	 *
	 * @return PaymentRequest
	 */
	public function addReasonCode( $reasonCode ) {
		$this->reasonCode = $reasonCode;

		return $this;
	}


	/**
	 * {@inheritdoc}
	 */
	public function toXml() {
		return XmlUtils::toXml( $this, new MessageType( MessageType::PAYMENT ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function fromXml( $xml ) {
		return XmlUtils::fromXml( $xml, new MessageType( MessageType::PAYMENT ) );
	}


	/**
	 * {@inheritdoc}
	 */
	public function generateDefaults( $secret ) {

		//generate timestamp if not set
		if ( is_null( $this->timestamp ) ) {
			$this->timestamp = GenerationUtils::generateTimestamp();
		}

		//generate order ID if not set
		if ( is_null( $this->orderId ) ) {
			$this->orderId = GenerationUtils::generateOrderId();
		}

		//generate hash
		$this->hash( $secret );

		return $this;


	}

	/**
	 * {@inheritdoc}
	 */
	public function responseFromXml( $xml ) {

		$paymentResponse = new PaymentResponse();

		return $paymentResponse->fromXML( $xml );
	}

	/**
	 * Creates the security hash from a number of fields and the shared secret.
	 *
	 * @param string $secret
	 *
	 * @return PaymentRequest
	 */
	public function hash( $secret ) {

		//check for any null values and set them to empty string for hashing
		$timeStamp  = null == $this->timestamp ? "" : $this->timestamp;
		$merchantId = null == $this->merchantId ? "" : $this->merchantId;
		$orderId    = null == $this->orderId ? "" : $this->orderId;
		$amount     = "";
		$currency   = "";
		$token      = null == $this->token ? "" : $this->token;
		$payerRef   = null == $this->payerRef ? "" : $this->payerRef;

		if ( $this->amount != null ) {
			$amount   = null == $this->amount->getAmount() ? "" : $this->amount->getAmount();
			$currency = null == $this->amount->getCurrency() ? "" : $this->amount->getCurrency();
		}

		$cardNumber = "";

		if ( $this->card != null ) {
			$cardNumber = null == $this->card->getNumber() ? "" : $this->card->getNumber();
		}

		$payerNewRef = "";
		if ( $this->payer != null ) {
			$payerNewRef = null == $this->payer->getRef() ? "" : $this->payer->getRef();
		}

		$cardHolderName = "";

		if ( $this->card != null ) {
			$cardHolderName = null == $this->card->getCardHolderName() ? "" : $this->card->getCardHolderName();
		}

		$cardPayerRef = "";

		if ( $this->card != null ) {
			$cardPayerRef = null == $this->card->getPayerReference() ? "" : $this->card->getPayerReference();
		}

		$cardRef = "";

		if ( $this->card != null ) {
			$cardRef = null == $this->card->getReference() ? "" : $this->card->getReference();
		}

		$cardExpiryDate = "";

		if ( $this->card != null ) {
			$cardExpiryDate = null == $this->card->getExpiryDate() ? "" : $this->card->getExpiryDate();
		}

		//create String to hash
		if ( $this->type == PaymentType::AUTH_MOBILE ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "..."
			          . $token;
		} elseif ( $this->type == PaymentType::OTB ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "."
			          . $cardNumber;

		} elseif ( $this->type == PaymentType::RECEIPT_IN || $this->type == PaymentType::PAYMENT_OUT || $this->type == PaymentType::STORED_CARD_DCC_RATE ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "."
			          . $amount
			          . "."
			          . $currency
			          . "."
			          . $payerRef;

		} elseif ( $this->type == PaymentType::PAYER_NEW || $this->type == PaymentType::PAYER_EDIT ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "."
			          . $amount
			          . "."
			          . $currency
			          . "."
			          . $payerNewRef;

		} elseif ( $this->type == PaymentType::CARD_NEW ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "."
			          . $amount
			          . "."
			          . $currency
			          . "."
			          . $cardPayerRef
			          . "."
			          . $cardHolderName
			          . "."
			          . $cardNumber;

		} elseif ( $this->type == PaymentType::CARD_UPDATE ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $cardPayerRef
			          . "."
			          . $cardRef
			          . "."
			          . $cardExpiryDate
			          . "."
			          . $cardNumber;

		} elseif ( $this->type == PaymentType::CARD_CANCEL ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $cardPayerRef
			          . "."
			          . $cardRef;

		} elseif ( $this->type == PaymentType::RECEIPT_IN_OTB ) {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "."
			          . $payerRef;

		} else {
			$toHash = $timeStamp
			          . "."
			          . $merchantId
			          . "."
			          . $orderId
			          . "."
			          . $amount
			          . "."
			          . $currency
			          . "."
			          . $cardNumber;
		}

		$this->hash = GenerationUtils::generateHash( $toHash, $secret );

		return $this;

	}

	/**
	 * <p>
	 * This helper method adds Address Verification Service (AVS) fields to the request.
	 * </p>
	 * <p>
	 * The Address Verification Service (AVS) verifies the cardholder's address by checking the
	 * information provided by at the time of sale against the issuing bank's records.
	 * </p>
	 *
	 * @param string $addressLine
	 * @param string $postcode
	 * @param string $country
	 *
	 * @return PaymentRequest
	 */
	public function addAddressVerificationServiceDetails( $addressLine, $postcode, $country ) {

		//build code in format <digits from postcode>|<digits from address>
		$postcodeDigits    = preg_replace( "/\\D+/", "", $postcode );
		$addressLineDigits = preg_replace( "/\\D+/", "", $addressLine );
		$code              = $postcodeDigits . "|" . $addressLineDigits;
		//construct billing address from code
		$address = new Address();

		$address->addCode( $code )
		        ->addCountry( $country )
		        ->addType( AddressType::BILLING );

		//add address to TSS Info
		if ( is_null( $this->tssInfo ) ) {
			$this->tssInfo = new TssInfo();
		}
		$this->tssInfo->addAddress( $address );

		return $this;
	}


}