<?php

namespace bubanga\Sms;


class Lvlup extends AbstractSms
{
    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key'],
            'request' => ['code', 'number']
        ];
    }

    public function checkRequest():bool
    {
        if (!$this->checkCode($this->request['code'], 8) || !$this->getRequiredParams())
            return false;

        $url = "https://lvlup.pro/api/checksms?id=" . $this->secret['api_key'] . "&code=" . $this->request['code'] . "&number=" . $this->request['number'];
        $api = $this->sendGetRequest($url);

        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if (isset($api['valid']) && $api['valid']) {

            return true;
        }

        $this->setError(self::$error_code[2], 0);
        return false;
    }
}