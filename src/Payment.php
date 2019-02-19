<?php

namespace bubanga;


use bubanga\Paypal\AbstractPaypal;
use bubanga\Paysafecard\AbstractPaysafecard;
use bubanga\Sms\AbstractSms;
use bubanga\Transfer\AbstractTransfer;
use bubanga\PaymentException;

class Payment
{
    private $payment;

    /**
     * @param AbstractSms $payment
     * @param array $secret
     * @param array $request
     * @param array|null $action
     */
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

    /**
     * @return mixed
     * @throws \bubanga\PaymentException
     */
    public function getPaymentSMS ()
    {
        if (isset($this->payment['sms']))
            return $this->payment['sms'];

        throw new PaymentException(''); //TODO
    }

    /**
     * @param AbstractPaysafecard $payment
     * @param array $secret
     * @param array $request
     * @param array|null $response
     */
    public function setPaymentPSC(AbstractPaysafecard $payment, array $secret, array $request, ?array $response = null)
    {
        $this->payment['psc'] = $payment;
        $this->payment['psc']->setSecret($secret);
        $this->payment['psc']->setRequest($request);

        if (isset($response) && is_array($response))
            $this->payment['psc']->setResponse($response);
    }

    /**
     * @return mixed
     * @throws \bubanga\PaymentException
     */
    public function getPaymentPSC ()
    {

        if (isset($this->payment['psc']))
            return $this->payment['psc'];

        throw new PaymentException(''); //TODO

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