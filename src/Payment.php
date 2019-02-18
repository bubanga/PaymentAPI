<?php

namespace bubanga;


use bubanga\Sms\AbstractSms;

class Payment
{
    private $paymentSMS;
    public function setPaymentSMS(AbstractSms $payment, array $secret, array $request)
    {
        $this->paymentSMS = $payment;
        $this->paymentSMS->setSecret($secret);
        $this->paymentSMS->setRequest($request);
    }

    public function getPaymentSMS ()
    {
        return $this->paymentSMS;
    }
}