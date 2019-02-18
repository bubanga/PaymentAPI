<?php

namespace bubanga\Sms;


class HotPay extends AbstractSms
{
    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key'],
            'request' => ['code',]
        ];
    }

    public function checkRequest(): bool
    {
        if (!$this->checkCode($this->request['code'], 8) || !$this->getRequiredParams())  //TODO @length SMS code
            return false;

        $url = "https://api.hotpay.pl/check_sms.php?sekret=" . $this->secret['api_key'] . "&kod_sms=" . $this->request['code'];
        $api = $this->sendGetRequest($url);
        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if (isset($api['status']) && $api['status'] == "SUCCESS") {

            return true;
        }

        $this->setError($api['tresc'], 0);
        return false;
    }
}