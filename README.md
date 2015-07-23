# rxp-remote-php
The official PHP Remote SDK of Realex Payments


## Requeriments ##
- PHP 5.5
- Composer (https://getcomposer.org/)

## Instructions ##

1. In you application root folder checkout the latest version of the project from Git:
```
    git clone https://github.com/realexpayments-developers/rxp-remote-php.git
```

2. Inside the rxp-remote-php directory run composer:
```
    composer update
```

3. Add a reference to the autoloader class anywhere where you need to use the sdk
```
    require __DIR__ . "/rxp-remote-php/vendor/autoload.php";
```

4. Use the sdk <br/>
```
 $card = ( new Card() )
         ->addNumber( "4263971921001307" )                                                                                                                                                                                              
         ->addCardHolderName( "JoeBloggs" )
         ....
```

##Example of an index.php page##

```php                                                                                    
require __DIR__ . "/rxp-remote-php/vendor/autoload.php";
        
use com\realexpayments\remote\sdk\domain\Card;                                            
use com\realexpayments\remote\sdk\domain\CardType;                                        
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;                              
use com\realexpayments\remote\sdk\domain\payment\AutoSettleFlag;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;                          
use com\realexpayments\remote\sdk\domain\payment\PaymentType;                             
use com\realexpayments\remote\sdk\RealexClient;
                                                                                          
// test payment                                                                           
                                                                                          
try {                                                                                    
        $card = ( new Card() )                                                            
                ->addNumber( "4263971921001307" )                                         
                ->addCardHolderName( "JoeBloggs" )                                        
                ->addType( CardType::VISA )                                               
                ->addExpiryDate( "1220" );                                                
                                                                                          
        $request = ( new PaymentRequest )                                                 
                ->addType( PaymentType::AUTH )                                            
                ->addCard( $card )                                                        
                ->addMerchantId( "merchant123" )                                       
                ->addAccount( "internet" )                                                
                ->addAmount( 50 )                                                         
                ->addCurrency( "EUR" )                                                    
                ->addAutoSettle( ( new AutoSettle() )->addFlag( AutoSettleFlag::TRUE ) ); 
                                                                                          
                                                                                          
        $client   = new RealexClient( "secret" );                                     
        $response = $client->send( $request );                                            
    } catch ( Exception $e ) {                                                                
        echo $e->getMessage();                                                                             
    }                       
```
                                                                                          
                                                                                          

