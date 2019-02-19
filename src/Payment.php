<?php

namespace bubanga;


use bubanga\Paypal\AbstractPaypal;
use bubanga\Paysafecard\AbstractPaysafecard;
use bubanga\Sms\AbstractSms;
use bubanga\Transfer\AbstractTransfer;

class Payment
{
    private $payment;

    public function setPaymentSMS(AbstractSms $payment, array $secret, array $request, ?array $action = null)
    {
        $this->payment['sms'] = $payment;
        $this->payment['sms']->setSecret($secret);
        $this->payment['sms']->setRequest($request);

        /*$action = [
            'params' => '',
            'func' => '',
            'class' => '',
            'method' => ''
        ];*/

        if (isset($action) && is_array($action))
            $this->payment['sms']->setAction($action);
    }

    public function getPaymentSMS ()
    {
        return $this->payment['sms'];
    }

    public function setPaymentPSC(AbstractPaysafecard $payment)
    {
        $this->payment['psc'] = $payment;
    }

    public function getPaymentPSC ()
    {
        return $this->payment['psc'];
    }

    public function setPaymentPP(AbstractPaypal $payment)
    {
        $this->payment['pp'] = $payment;
    }

    public function getPaymentPP ()
    {
        return $this->payment['pp'];
    }

    public function setPaymentTransfer(AbstractTransfer $payment)
    {
        $this->payment['transfer'] = $payment;
    }

    public function getPaymentTransfer ()
    {
        return $this->payment['transfer'];
    }
}