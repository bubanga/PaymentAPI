<?php

namespace bubanga\Paysafecard;


class Rushpay extends AbstractPaysafecard
{

    public function getRequiredParams(bool $response = false):array
    {
        if ($response) {
            return [
                'secret' => ['api_key', 'api_secret'],
                'request' => ['shop_id', 'price']
            ];
        }

        return [
            'secret' => ['api_key', 'api_secret'],
            'request' => ['shop_id', 'price', 'return_ok', 'return_fail', 'response']
        ];
    }

    public function checkResponse(bool $authorization = true):bool
    {
        if (!$this->requestAuthorization() && $authorization)
            return false;

        if ($this->response['status'])
            return true;

        return false;
    }

    public function getData (bool $provider = false):?array
    {
        if ($provider)
            return array('https://www.rushpay.pl/api/psc/');

        $response = false;

        if (isset($this->response) && is_array($this->response))
            $response = true;

        if (!$this->checkRequiredParams($response))
            return null;

        return [
            'userid'        => $this->secret['api_key'],
            'shopid'        => $this->request['shop_id'],
            'amount'        => $this->request['price'],
            'return_ok'     => $this->request['return_ok'],
            'return_fail'   => $this->request['return_fail'],
            'url'           => $this->request['response'],
            'control'       => "",
            'hash'          => $this->hash()
        ];
    }

    public function requestAuthorization(): bool
    {
        if (!$this->validDateIpn() || $this->validDateUser())
        {
            $this->setError(self::$error_code[1], 1);
            return false;
        }

        return true;
    }

    private function hash ():string
    {
        return hash('sha256', $this->secret['api_key'] . $this->secret['api_secret'] . $this->request['price']);
    }

    private function validDateIpn():bool
    {
        if (!in_array($_SERVER['REMOTE_ADDR'], explode(',', file_get_contents("https://rushpay.pl/psc/ips/"))) == TRUE)
            return false;

        return true;
    }

    private function validDateUser():bool
    {
        if ($this->secret['api_key'] != $this->response['userid'] || $this->request['shop_id'] != $this->response['shopid'])
            return false;

        return true;

    }
}