<?php

namespace bubanga\Sms;


class Cashbill extends AbstractSms
{
    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key'],
            'request' => ['code']
        ];
    }


    public function checkRequest(): bool
    {
        if (!$this->checkCode($this->request['code'], 8) || !$this->getRequiredParams())  //TODO @length SMS code
            return false;

        $url = "https://sms.cashbill.pl/code/" . $this->secret['api_key'] . "/" . $this->request['code'];
        $api = $this->sendGetRequest($url);

        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if ($api['active'])
            return true;


        if (!isset($api['error']))
            $api['error'] = self::$error_code[0];

        $this->setError($api['error'], 0);
        return false;
    }
}