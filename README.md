# rxp-remote-php
The official PHP Remote SDK of Realex Payments

## Requirements ##
- PHP >= 5.3
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
```
    require_once ('vendor/autoload.php');
```

4. Use the sdk <br/>
```php
 $card = ( new Card() )
         ->addNumber( "4263971921001307" )                                                                                                                                                                                              
         ->addCardHolderName( "JoeBloggs" )
         ....
```

##Example of an index.php page##

```php                                                                                    
require_once ('vendor/autoload.php');
        
use com\realexpayments\remote\sdk\domain\Card;                                            
use com\realexpayments\remote\sdk\domain\CardType;                                        
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;                              
use com\realexpayments\remote\sdk\domain\payment\AutoSettleFlag;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;                          
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
        ->addMerchantId( "merchant123" )                                       
        ->addAccount( "internet" )                                                
        ->addAmount( "50" )                                                         
        ->addCurrency( "EUR" )                                                    
        ->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) ); 
                                                                                  
                                                                                  
$client   = new RealexClient( "secret" );                                     
$response = $client->send( $request );

// do something with the response
echo $response->toXML();
                           
```
                                                                                          
                                                                                          

