<?php

namespace bubanga\Paysafecard;


class Hotpay extends AbstractPaysafecard
{
    public function getRequiredParams(bool $response = false): array
    {
        // TODO: Implement getRequiredParams() method.
    }

    public function checkResponse(bool $authorization = true)
    {
        // TODO: Implement checkResponse() method.
    }

    public function getData (bool $provider = false):?array
    {
        if ($provider)
            return array('https://psc.hotpay.pl/'); //TODO or https://platnosc.hotpay.pl/

        $response = false;

        if (isset($this->response) && is_array($this->response))
            $response = true;

        if (!$this->checkRequiredParams($response))
            return null;

        return [
            'SEKRET'        => $this->secret['api_secret'],
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
        // TODO: Implement requestAuthorization() method.
    }

    private function hash ():string
    {
        return hash("sha256",
            "HASHZUSTAWIEN;" .
            $_POST["KWOTA"] . ";" .
            $_POST["ID_PLATNOSCI"] . ";" .
            $_POST["ID_ZAMOWIENIA"] . ";" .
            $_POST["STATUS"] . ";" .
            $_POST["SEKRET"]);
    }
}