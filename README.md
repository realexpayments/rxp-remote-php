# Realex Payments Remote PHP SDK
You can sign up for a free Realex Payments sandbox account at https://www.realexpayments.co.uk/developers

## Requirements
- PHP >= 5.3.9
- Composer (https://getcomposer.org/)

## Instructions

1. Add the following to your 'composer.json' file

    ```
    {
        "require": {
            "realexpayments/rxp-remote-php": "1.0.0"
        }    
    }
    ```

2. Inside the application directory run composer:

    ```
    composer update
    ```

    OR (depending on your server configuration)

    ```
    php composer.phar update
    ```

3. Add a reference to the autoloader class anywhere you need to use the sdk

    ```php
    require_once ( 'vendor/autoload.php' );
    ```

4. Use the sdk <br/>

    ```php
	$card = ( new Card() )                                                            
			->addType( CardType::VISA ) 
			->addNumber( "4263971921001307" ) 
        ....
    ```


## Usage

### Authorisation


```php                                                                                    
require_once ( 'vendor/autoload.php' );

use com\realexpayments\remote\sdk\domain\Card;                                            
use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\PresenceIndicator;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;                              
use com\realexpayments\remote\sdk\domain\payment\AutoSettleFlag;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;                   
use com\realexpayments\remote\sdk\domain\payment\PaymentType;                             
use com\realexpayments\remote\sdk\RealexClient;
                                                                                   
$card = ( new Card() )                                                            
        ->addType( CardType::VISA ) 
		->addNumber( "4263971921001307" )                                         
        ->addExpiryDate( "1220" )
		->addCvn( "123" )
		->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
		->addCardHolderName( "James Mason" );                                     
                                                                                
$request = ( new PaymentRequest() )                                                 
        ->addType( PaymentType::AUTH )                                            
        ->addCard( $card )                                                        
        ->addMerchantId( "myMerchantId" )                                       
        ->addAccount( "mySubAccount" )                                                
        ->addAmount( 1001 )                                                         
        ->addCurrency( "EUR" )                                                    
        ->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) ); 
                                                                                  
                                                                                  
$client   = new RealexClient( "mySecret" );                                     
$response = $client->send( $request );

// do something with the response
echo $response->toXML();

$resultCode = $response->getResult();
$message = $response->getMessage();
                           
```

### Authorisation (With Address Verification)

```php
$card = ( new Card() )
	->addExpiryDate( "1220" )
	->addNumber( "4263971921001307" )
	->addType( CardType::VISA )
	->addCardHolderName( "Joe Smith" );
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
 
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )
	->addType( PaymentType::AUTH )
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addCard( $card )       
	->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) )
	->addAddressVerificationServiceDetails("382 The Road", "WB1 A42");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Authorisation (Mobile)

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::AUTH_MOBILE )
	->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) )
	->addMobile("apple-pay")
	->addToken("{auth mobile payment token}");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```


### Settle

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::SETTLE )
	->addOrderId("Order ID from original transaction")
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addPaymentsReference("pasref from original transaction")
	->addAuthCode("Auth code from original transaction");


$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Void

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::VOID )
	->addOrderId("Order ID from original transaction")
	->addPaymentsReference("pasref from original transaction")
	->addAuthCode("Auth code from original transaction");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Rebate

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::REBATE )
	->addOrderId("Order ID from original transaction")
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addPaymentsReference("pasref from original transaction")
	->addAuthCode("Auth code from original transaction")
	->addRefundHash("Hash of rebate password shared with Realex");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### OTB

```php
$card = ( new Card() )
	->addExpiryDate( "1220" )
	->addNumber( "4263971921001307" )
	->addType( CardType::VISA )
	->addCardHolderName( "Joe Smith" );
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
 
 $request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::OTB )
	->addCard( $card );
	
$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );	
```

### Refund

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::REFUND )
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addPaymentsReference("Pasref from original transaction")
	->addAuthCode("Auth code from original transaction")
	->addRefundHash("Hash of credit password shared with Realex");
 
$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Hold

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::HOLD )
	->addOrderId("Order ID from original transaction")
	->addReasonCode( ReasonCode::FRAUD)
	->addPaymentsReference("Pasref from original transaction");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Release

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::RELEASE )
	->addOrderId("Order ID from original transaction")
	->addReasonCode( ReasonCode::FRAUD)
	->addPaymentsReference("Pasref from original transaction");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```
### Receipt-In

```php
$paymentData = ( new PaymentData() )
  	->addCvnNumber("123");

$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::RECEIPT_IN)
 	->addAmount(100)
 	->addCurrency( "EUR" )        
 	->addPayerReference("payer ref from customer")
 	->addPaymentMethod("payment method ref from customer")
 	->addPaymentData($paymentData);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Payment-out

```php
$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::PAYMENT_OUT) 	
 	->addAmount(100)
 	->addCurrency( "EUR" )        
 	->addPayerReference("payer ref from customer")
 	->addPaymentMethod("payment method ref from customer")
 	->addRefundHash("Hash of rebate password shared with Realex");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Payer-new

```php
$address = ( new PayerAddress() )
    ->addLine1("Apt 167 Block 10")
    ->addLine2("The Hills")
    ->addLine3("67-69 High St")
    ->addCity("Hytown")
    ->addCounty("Dunham")
    ->addPostcode("3")
    ->addCountryCode("IE")
    ->addCountryName("Ireland");
    

$payer = ( new Payer() )
    ->addType("Business")
    ->addRef("smithj01")
    ->addTitle("Mr")
    ->addFirstName("John")
    ->addSurname("Smith")
    ->addCompany("Acme")
    ->addAddress($address)
    ->addHomePhoneNumber("+35317285355")
    ->addWorkPhoneNumber("+35317433923")
    ->addFaxPhoneNumber("+35317893248")
    ->addMobilePhoneNumber("+353873748392")
    ->addEmail("jsmith@acme.com")
    ->addComment("Comment1")
    ->addComment("Comment2");

$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::PAYER_NEW)  	
 	->addPayer($payer);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Payer-edit

```php
$address = ( new PayerAddress() )
    ->addLine1("Apt 167 Block 10")
    ->addLine2("The Hills")
    ->addLine3("67-69 High St")
    ->addCity("Hytown")
    ->addCounty("Dunham")
    ->addPostcode("3")
    ->addCountryCode("IE")
    ->addCountryName("Ireland");
    

$payer = ( new Payer() )
    ->addType("Business")
    ->addRef("smithj01")
    ->addTitle("Mr")
    ->addFirstName("John")
    ->addSurname("Smith")
    ->addCompany("Acme")
    ->addAddress($address)
    ->addHomePhoneNumber("+35317285355")
    ->addWorkPhoneNumber("+35317433923")
    ->addFaxPhoneNumber("+35317893248")
    ->addMobilePhoneNumber("+353873748392")
    ->addEmail("jsmith@acme.com")
    ->addComment("Comment1")
    ->addComment("Comment2");

$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::PAYER_EDIT)  	
 	->addPayer($payer);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Card-new

```php
$card = ( new Card() )
    ->addReference("visa01")
    ->addPayerReference("smithj01")
    ->addNumber("420000000000000000")    
	->addExpiryDate("0119")	
	->addCardHolderName("Joe Smith")
	->addType(CardType::VISA)
	->addIssueNumber("1");

$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::CARD_NEW) 	
 	->addCard($card);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Card-edit

```php
$card = ( new Card() )
    ->addReference("visa01")
    ->addPayerReference("smithj01")
    ->addNumber("420000000000000000")    
	->addExpiryDate("0119")	
	->addCardHolderName("Joe Smith")
	->addType(CardType::VISA)
	->addIssueNumber("1");

$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::CARD_UPDATE)  
 	->addPayerReference( "smithj01" )
 	->addCard($card);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```


### Card-delete

```php
$card = ( new Card() )
    ->addReference("visa01")
    ->addPayerReference("smithj01");

$request = ( new PaymentRequest() )
 	->addAccount( "myAccount" )
 	->addMerchantId( "myMerchantId" )	
 	->addType(PaymentType::CARD_CANCEL)  	
 	->addCard($card);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Verify Card Enrolled

```php
$paymentData = new PaymentData()
  	->addCvnNumber("123");


$request = ( new ThreeDSecureRequest() )
  ->addAccount( "myAccount" )
  ->addMerchantId( "myMerchantId" )	
  ->addType(ThreeDSecureType::VERIFY_CARD_ENROLLED)
  ->addAmount(100)
  ->addCurrency( "EUR" )        
  ->addPayerReference("payer ref from customer")
  ->addPaymentMethod("payment method ref from customer")
  ->addPaymentData($paymentData)
  ->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) );
 	

$client   = new RealexClient( "mySecret" );
$response = client.send(request);
```

### DCC Rate Lookup

```php
$card = ( new Card() )    
    ->addNumber("420000000000000000")    
	->addExpiryDate("0119")	
	->addCardHolderName("Joe Smith")
	->addType(CardType::VISA);
	
$dccInfo = ( new DccInfo() )
    ->addDccProcessor("fexco");

$request = ( new PaymentRequest() )
  ->addAccount( "myAccount" )
  ->addMerchantId( "myMerchantId" )	
  ->addType(PaymentType::DCC_RATE_LOOKUP)
  ->addAmount(100)
  ->addCurrency( "EUR" )        
  ->addCard($card)
  ->addDccInfo($dccInfo);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```


### DCC + Auth

```php
$card = ( new Card() )    
    ->addNumber("420000000000000000")    
	->addExpiryDate("0119")	
	->addCardHolderName("Joe Smith")
	->addType(CardType::VISA);
	
$dccInfo = ( new DccInfo() )
    ->addDccProcessor("fexco")
    ->addRate(0.6868)
    ->addAmount(13049)
    ->addCurrency("GBP");

$request = ( new PaymentRequest() )
  ->addAccount( "myAccount" )
  ->addMerchantId( "myMerchantId" )	
  ->addType(PaymentType::DCC_AUTH)
  ->addAmount(19000)
  ->addCurrency( "EUR" )        
  ->addCard($card)
  ->addDccInfo($dccInfo);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Receipt-in OTB

```php 
$request = ( new PaymentRequest() )
    ->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType(PaymentType::RECEIPT_IN_OTB)
	->addPayerReference("payer ref from customer")
    ->addPaymentMethod("payment method ref from customer");
	
$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );	
```

### DCC Stored Card Dcc Rate

```php 
$card = ( new Card() )    
    ->addNumber("420000000000000000")    
	->addExpiryDate("0119")	
	->addCardHolderName("Joe Smith")
	->addType(CardType::VISA);
	
$dccInfo = ( new DccInfo() )
    ->addDccProcessor("fexco")
    ->addRate(0.6868)
    ->addAmount(13049)
    ->addCurrency("GBP");

$request = ( new PaymentRequest() )
  ->addAccount( "myAccount" )
  ->addMerchantId( "myMerchantId" )	
  ->addType(PaymentType::STORED_CARD_DCC_RATE)
  ->addAmount(19000)
  ->addCurrency( "EUR" )        
  ->addCard($card)
  ->addDccInfo($dccInfo);

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

### Fraud Filter Request

```php 
$fraudFilter = new FraudFilter();
$fraudFilter->addMode(FraudFilterMode::ACTIVE);

$card = ( new Card() )    
    ->addNumber("420000000000000000")    
	->addExpiryDate("0119")	
	->addCardHolderName("Joe Smith")
	->addType(CardType::VISA);
	
$autoSettle = new AutoSettle();
$autoSettle->addFlag(AutoSettleFlag::TRUE);
	
$request = ( new PaymentRequest() )
    ->addType( PaymentType::AUTH )
    ->addCard( $card )
    ->addAccount( "myAccount" )
    ->addMerchantId( "myMerchantId" )
    ->addAmount( 1000 )
    ->addCurrency( "EUR" )
    ->addOrderId("myOrderId")
    ->addAutoSettle( $autoSettle)
    ->addFraudFilter($fraudFilter);

	
$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );	
```

### Fraud Filter Response

```php 
// request is fraud filter
$response = $client->send( $request );	

$mode = $response->getFraudFilter()->getMode();
$result = $response->getFraudFilter()->getResult();
//array of FraudFilterResultRule
$rules = $response->getFraudFilter()->getRules();
foreach($rules as $rule)
{
    echo $rule->getId();
    echo $rule->getName();
    echo $rule->getValue();
}
//or
echo $rules->get(0)->getId();
```

## License

See the LICENSE file.                                                                                         
                                                                                          

