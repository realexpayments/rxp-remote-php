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
            "realexpayments/rxp-remote-php": "1.2.2"
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
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )                                                  
    ->addType( PaymentType::AUTH )                                            
    ->addCard( $card )                                                                                      
    ->addAmount( 1001 )                                                         
    ->addCurrency( "EUR" )                                                    
    ->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) ); 
                                                                                  
                                                                                  
$client   = new RealexClient( "Shared Secret" );                                     
$response = $client->send( $request );

// do something with the response
echo $response->toXML();

$resultCode = $response->getResult();
$message = $response->getMessage();
                           
```

### Authorisation (With Address Verification)

```php
$card = ( new Card() )
	->addNumber( "4263971921001307" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" );
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
	->addExpiryDate( "1220" );
 
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType( PaymentType::AUTH )
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addCard( $card )       
	->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) )
	->addAddressVerificationServiceDetails("382 The Road", "WB1 A42");

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Authorisation (Mobile)

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType( PaymentType::AUTH_MOBILE )
	->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) )
	->addMobile("apple-pay")
	->addToken("{auth mobile payment token}");

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```


### Settle

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
	->addType( PaymentType::SETTLE )
	->addOrderId("Order ID from original transaction")
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addPaymentsReference("pasref from original transaction");


$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Void

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
	->addType( PaymentType::VOID )
	->addOrderId("Order ID from original transaction")
	->addPaymentsReference("pasref from original transaction");

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Rebate

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
	->addType( PaymentType::REBATE )
	->addOrderId("Order ID from original transaction")
	->addAmount( 1001 )           
	->addCurrency( "EUR" )                  
	->addPaymentsReference("pasref from original transaction")
	->addAuthCode("Auth code from original transaction")
	->addRefundHash("SHA1 hash of rebate password");

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### OTB

```php
$card = ( new Card() )
	->addNumber( "4263971921001307" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" )
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
	->addExpiryDate( "1220" );
 
 $request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
	->addType( PaymentType::OTB )
	->addCard( $card );
	
$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );	
```

### Refund

```php
$card = ( new Card() )
	->addNumber( "4263971921001307" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" )
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
	->addExpiryDate( "1220" );
	
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType( PaymentType::REFUND )
	->addAmount( 1001 )           
	->addCurrency( "EUR" )
	->addCard( $card )
	->addRefundHash( "Hash of credit password shared with Realex" );
 
$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Hold

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType( PaymentType::HOLD )
	->addOrderId( "Order ID from original transaction" )
	->addReasonCode( ReasonCode::FRAUD)
	->addPaymentsReference( "Pasref from original transaction" );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Release

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType( PaymentType::RELEASE )
	->addOrderId( "Order ID from original transaction" )
	->addReasonCode( ReasonCode::FRAUD)
	->addPaymentsReference( "Pasref from original transaction" );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```
### Receipt-In

```php
$paymentData = ( new PaymentData() )
  	->addCvnNumber( "123" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
 	->addType( PaymentType::RECEIPT_IN )
 	->addAmount( 100 )
 	->addCurrency( "EUR" )        
 	->addPayerReference( "payer ref for customer" )
 	->addPaymentMethod( "payment method ref for card" )
 	->addPaymentData( $paymentData );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Payment-out

```php
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
 	->addType( PaymentType::PAYMENT_OUT ) 	
 	->addAmount( 100 )
 	->addCurrency( "EUR" )        
 	->addPayerReference( "payer ref for customer" )
 	->addPaymentMethod( "payment method ref for card" )
 	->addRefundHash("Hash of rebate password shared with Realex");

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Payer-new

```php
$address = ( new PayerAddress() )
    ->addLine1( "Flat 123" )
    ->addLine2( "House 456" )
    ->addLine3( "The Cul-De-Sac" )
    ->addCity( "Halifax" )
    ->addCounty( "West Yorkshire" )
    ->addPostcode( "W6 9HR" )
    ->addCountryCode( "GB" )
    ->addCountryName( "United Kingdom" );
    

$payer = ( new Payer() )
    ->addType( "Business" )
    ->addRef( "0f357b45-9aa4-4453-a685-c69232e9024f" )
    ->addTitle( "Mr" )
    ->addFirstName( "James" )
    ->addSurname( "Mason" )
    ->addCompany( "Realex Payments" )
    ->addAddress( $address )
    ->addHomePhoneNumber( "+35317285355" )
    ->addWorkPhoneNumber( "+35317433923" )
    ->addFaxPhoneNumber( "+35317893248" )
    ->addMobilePhoneNumber( "+353873748392" )
    ->addEmail( "test@example.com" )
    ->addComment( "Campaign Ref E7373G" )
    ->addComment( "Website Sign-Up" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
 	->addType( PaymentType::PAYER_NEW )  	
 	->addPayer( $payer );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Payer-edit

```php
$address = ( new PayerAddress() )
    ->addLine1( "Flat 456"  )
    ->addLine2( "House 321" )
    ->addLine3( "Basement Flat" )
    ->addCity( "Rathmines" )
    ->addCounty( "Dublin" )
    ->addPostcode( "6" )
    ->addCountryCode( "IE" )
    ->addCountryName( "Ireland" );
    

$payer = ( new Payer() )
    ->addType( "Subscriber" )
    ->addRef( "0f357b45-9aa4-4453-a685-c69232e9024f" )
    ->addTitle( "Mr" )
    ->addFirstName( "Philip" )
    ->addSurname( "Marlowe" )
    ->addCompany( "Pinkerton" )
    ->addAddress( $address )
    ->addHomePhoneNumber( "+3538547854" )
    ->addWorkPhoneNumber( "+3535611177" )
    ->addFaxPhoneNumber( "+12459498488" )
    ->addMobilePhoneNumber( "+2554457774" )
    ->addEmail( "test123@example.com" )
    ->addComment( "New Subscription" )
    ->addComment( "12 Months" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
 	->addType(PaymentType::PAYER_EDIT)  	
 	->addPayer( $payer );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Card-new

```php
$card = ( new Card() )
    ->addReference( "10c6a2c7-be7b-a13f-12638937a012" )
    ->addPayerReference( "0f357b45-9aa4-4453-a685-c69232e9024f" )
	->addNumber( "4263971921001307" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" )
	->addExpiryDate( "1220" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
 	->addType( PaymentType::CARD_NEW ) 	
 	->addCard( $card );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Card-edit

```php
$card = ( new Card() )
	->addReference( "10c6a2c7-be7b-a13f-12638937a012" )
	->addPayerReference( "0f357b45-9aa4-4453-a685-c69232e9024f" )
	->addNumber( "5425230000004415" )
	->addType( CardType::MASTERCARD )
	->addCardHolderName( "James Mason" )
	->addExpiryDate( "1220" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
 	->addType( PaymentType::CARD_UPDATE )  
 	->addCard( $card );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```


### Card-delete

```php
$card = ( new Card() )
	->addReference( "10c6a2c7-be7b-a13f-12638937a012" )
	->addPayerReference( "0f357b45-9aa4-4453-a685-c69232e9024f" )

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
 	->addType( PaymentType::CARD_CANCEL )  	
 	->addCard($card);

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Verify Stored Card Enrolled

```php
$paymentData = new PaymentData()
  	->addCvnNumber( "123" );


$request = ( new ThreeDSecureRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType( ThreeDSecureType::VERIFY_STORED_CARD_ENROLLED )
	->addAmount( 100 )
	->addCurrency( "EUR" )        
	->addReference( "10c6a2c7-be7b-a13f-12638937a012" )
	->addPayerReference( "0f357b45-9aa4-4453-a685-c69232e9024f" )
	->addPaymentData( $paymentData )
	->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) );
 	

$client   = new RealexClient( "Shared Secret" );
$response = $client->send($request);
```

### DCC Rate Lookup

```php
$card = ( new Card() )
	->addNumber( "4006097467207025" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" )
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
	->addExpiryDate( "1220" );
	
$dccInfo = ( new DccInfo() )
    ->addDccProcessor( "fexco" )
    ->addRateType( "S" )
	->addType( "1" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
  	->addType( PaymentType::DCC_RATE_LOOKUP )
  	->addAmount(100)
  	->addCurrency( "EUR" )        
  	->addCard( $card )
  	->addDccInfo( $dccInfo );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```


### Authorisation with DCC Information

```php
$card = ( new Card() )
	->addNumber( "4006097467207025" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" )
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
	->addExpiryDate( "1220" );
	
$dccInfo = ( new DccInfo() )
    ->addDccProcessor( "fexco" )
    ->addRateType( "S" )
	->addType( "1" );
    ->addRate( 0.6868 )
    ->addAmount( 13049 )
    ->addCurrency( "AUD" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )
	->addType(PaymentType::DCC_AUTH)
	->addAmount( 19000 )
	->addCurrency( "EUR" )        
	->addCard( $card )
	->addDccInfo( $dccInfo );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Receipt-In OTB

```php 
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
	->addType( PaymentType::RECEIPT_IN_OTB )
	->addPayerReference( "03e28f0e-492e-80bd-20ec318e9334" )
    ->addPaymentMethod( "3c4af936-483e-a393-f558bec2fb2a" );
	
$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );	
```

### Stored Card DCC Rate Lookup

```php 
	
$dccInfo = ( new DccInfo() )
    ->addDccProcessor( "fexco" )
    ->addRateType( "S" )
	->addType( "1" );

$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
	->addType(PaymentType::STORED_CARD_DCC_RATE)
	->addAmount( 1001 )
	->addCurrency( "EUR" )        
	->addPayerReference( "03e28f0e-492e-80bd-20ec318e9334" )
    ->addPaymentMethod( "3c4af936-483e-a393-f558bec2fb2a" )
	->addDccInfo( $dccInfo );

$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );
```

### Fraud Filter Request

```php 

$card = ( new Card() )
	->addNumber( "4006097467207025" )
	->addType( CardType::VISA )
	->addCardHolderName( "James Mason" )
	->addCvn( "123" )
	->addCvnPresenceIndicator( PresenceIndicator::CVN_PRESENT )
	->addExpiryDate( "1220" );
	
$request = ( new PaymentRequest() )
	->addMerchantId( "Merchant ID" )
	->addAccount( "internet" )	
    ->addType( PaymentType::AUTH )
    ->addCard( $card )
    ->addAmount( 10001 )
    ->addCurrency( "EUR" )
    ->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) )
    ->addFraudFilter( ( new FraudFilter() )->addMode( FraudFilterMode::PASSIVE ) );

	
$client   = new RealexClient( "Shared Secret" );
$response = $client->send( $request );	
```

### Fraud Filter Response

```php 
// request is fraud filter
$response = $client->send( $request );	

$mode = $response->getFraudFilter()->getMode();
$result = $response->getFraudFilter()->getResult();
//array of FraudFilter Rule Actions
$rules = $response->getFraudFilter()->getRules();
foreach($rules->getRules() as $rule)
{
    echo $rule->getId();
    echo $rule->getName();
    echo $rule->getAction();
}
//or
echo $rules->get(0)->getId();
```

## License

See the LICENSE file.                                                                              
