<?php

namespace bubanga\Transfer;


use \bubanga\PaySafeCard\AbstractPaysafecard;

class Mircosms extends AbstractPaysafecard
{

    public function getRequiredParams():array
    {
        /*
            return [
                'secret' => ['api_secret'],
            ];
         */
        return [
            'secret' => ['api_secret'],
            'request' => ['shop_id', 'price', 'response', 'email']
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
            return array('https://microsms.pl/api/bankTransfer/');

        if (!$this->checkRequiredParams())
            return null;

        return [
            'shopid'        => $this->request['shop_id'],
            'amount'        => $this->request['price'],
            'return_urlc'   => $this->request['response'],
            'return_url'    => $this->request['response'],
            'signature'     => md5($this->request['shop_id'] . $this->secret['api_key'] . $this->request['price']),
            'control'       => "",
            'email'         => $this->request['email'],
            'description'   => ""
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

    private function validDateIpn():bool
    {
        if (!in_array($_SERVER['REMOTE_ADDR'], explode(',', file_get_contents("https://microsms.pl/psc/ips/"))) == TRUE)
            return false;

        return true;
    }

    private function validDateUser():bool
    {
        if ($this->secret['api_secret'] != $this->response['userid'])
            return false;

        return true;

    }
}