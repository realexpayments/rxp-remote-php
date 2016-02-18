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

### Credit

```php
$request = ( new PaymentRequest() )
	->addAccount( "myAccount" )
	->addMerchantId( "myMerchantId" )	
	->addType( PaymentType::CREDIT )
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
	->addPaymentsReference("Pasref from original transaction");

$client   = new RealexClient( "mySecret" );
$response = $client->send( $request );
```

## License

See the LICENSE file.                                                                                         
                                                                                          

