

# PHP Payment API [![Packagist](https://img.shields.io/packagist/dt/bubanga/payment_api.svg)](https://packagist.org/packages/xpaw/php-minecraft-query)

This library can be used to query payment providers for code authorizations

**:warning: Library not tested.**

## Install

Use the console command:

```text
composer require bubanga/payment_api
```

## Providers SMS Premium

> *Cashbill*<br>
> *GetPay*<br>
> *HotPay*<br>
> *Lvlup*<br>
> *Mircosms*<br>
> *Przelewy*<br>
> *SimPay*

TODO:

> *DotPay*<br>
> *RushPay*

You must use these classes with the namespace `\bubanga\Sms\`

## Example
```php
<?php
    require_once ('vendor/autoload.php');

    use bubanga\Payment;
	
    $payment = new Payment();
    $secret = array('api_key' => 123);
    $request = array ('code' => 'abcd1234', 'service_id' => 99);
	
    $payment->setPaymentSMS(new \bubanga\Sms\Microsms(), $secret, $request);
    if ($payment->getPaymentSMS()->getResult()){
        echo "code is ok";
    } else {
        echo "error: " . 
            $payment->getPaymentSMS()->getError()['number'] . ' ' . 
            $payment->getPaymentSMS()->getError()['text']
        ;
    }
?>
```

## Documentation
###Providers SMS Premium
####Cashbill
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "");
    $request = array ('code' => "");
	
    $payment->setPaymentSMS(new \bubanga\Sms\Cashbill(), $secret, $request);
```
---
####GetPay
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "", 'api_secret' => "");
    $request = array ('code' => "", 'number' => "", 'unlimited' => false);
	
    $payment->setPaymentSMS(new \bubanga\Sms\GetPay(), $secret, $request);
```
---
####HotPay
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "");
    $request = array ('code' => "");
	
    $payment->setPaymentSMS(new \bubanga\Sms\HotPay(), $secret, $request);
```
---
####Lvlup
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "");
    $request = array ('code' => "", 'number' => "");
	
    $payment->setPaymentSMS(new \bubanga\Sms\Lvlup(), $secret, $request);
```
---
####Mircosms
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "");
    $request = array ('code' => "", 'service_id' => "");
	
    $payment->setPaymentSMS(new \bubanga\Sms\Microsms(), $secret, $request);
```
---
####Przelewy
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "");
    $request = array ('code' => "", 'price' => "");
	
    $payment->setPaymentSMS(new \bubanga\Sms\Przelewy(), $secret, $request);
```
---
####SimPay
```php
<?php
    $payment = new \bubanga\Payment();
    $secret = array('api_key' => "", 'api_secret' => "");
    $request = array ('code' => "", 'number' => "", 'service_id' => "");
	
    $payment->setPaymentSMS(new \bubanga\Sms\SimPay(), $secret, $request);
```
###Providers PaySafeCard
###Providers PayPal
###Providers Transfer
## License
[Apache2.0](LICENSE)