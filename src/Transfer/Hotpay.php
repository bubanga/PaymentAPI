<?php

namespace bubanga\Transfer;


use \bubanga\PaySafeCard\AbstractPaysafecard;

class Hotpay extends AbstractPaysafecard
{
    public function getRequiredParams(): array
    {
        /*
            return [
                'secret' => ['api_key', 'api_secret']
            ];

         */
        return [
            'secret' => ['api_key', 'api_secret'],
            'request' => ['shop_id', 'price', 'name', 'data', 'response', 'email']
        ];
    }

    public function checkResponse(bool $authorization = true)
    {
        if (!$this->requestAuthorization() && $authorization)
            return false;

        if ($this->response['STATUS'] == "SUCCESS")
            return true;

        return false;
    }

    public function getData (bool $provider = false):?array
    {
        if ($provider)
            return array('https://platnosc.hotpay.pl/');

        if (!$this->checkRequiredParams())
            return null;

        return [
            'SEKRET'        => $this->request['secret'],
            'ID_ZAMOWIENIA' => $this->request['shop_id'],
            'KWOTA'         => $this->request['price'],
            'NAZWA_USLUGI'  => $this->request['name'],
            'DANE_OSOBOWE'  => $this->request['data'],
            'ADRES_WWW'     => $this->request['response'],
            'EMAIL'         => $this->request['email']
        ];
    }

    public function requestAuthorization(): bool
    {
        if ($this->response['HASH'] == $this->hash())
            return true;

        return false;
    }

    private function hash ():string
    {
        return hash("sha256",
            $this->secret['api_secret']. ";" .
            $this->response["KWOTA"] . ";" .
            $this->secret['api_key'] . ";" .
            $this->response["ID_ZAMOWIENIA"] . ";" .
            $this->response["STATUS"] . ";" .
            $this->response["SEKRET"]
        );
    }
}