<?php

namespace bubanga;


use bubanga\Sms\AbstractSms;

class Payment
{

    public function getPaymentSMS (AbstractSms $payment, array $secret, array $request):bool
    {
        $payment->setSecret($secret);
        $payment->setRequest($request);

        return $payment->getResponse();
    }
}