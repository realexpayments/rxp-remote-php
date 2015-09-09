# Realex Payments Remote PHP SDK
You can sign up for a free Realex Payments sandbox account at https://www.realexpayments.co.uk/developers

## Requirements ##
- PHP >= 5.3.9
- Composer (https://getcomposer.org/)

## Instructions ##

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


##SDK Example##

```php                                                                                    
require_once ( 'vendor/autoload.php' );
        
use com\realexpayments\remote\sdk\domain\Card;                                            
use com\realexpayments\remote\sdk\domain\CardType;                                        
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;                              
use com\realexpayments\remote\sdk\domain\payment\AutoSettleFlag;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;                   
use com\realexpayments\remote\sdk\domain\payment\PaymentType;                             
use com\realexpayments\remote\sdk\RealexClient;
                                                                                          
// test payment                                                                                                                                                                   
                                                                                   
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
                           
```

## License

See the LICENSE file.                                                                                         
                                                                                          

