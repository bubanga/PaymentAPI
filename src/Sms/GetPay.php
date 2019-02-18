<?php

namespace bubanga\Sms;


class GetPay extends AbstractSms
{

    private $infoEnum = array(
        100 => 'Empty method',
        102 => 'Empty params',
        104 => 'Wrong length of client API login data (key/secret)',
        105 => 'Wrong client API login data (key/secret)',
        106 => 'Wrong client status',
        107 => 'No method require params',
        200 => 'OK',
        400 => 'SMS code not found',
        401 => 'SMS code already used',
        402 => 'System error'
    );

    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key', 'api_secret'],
            'request' => ['code', 'number', 'unlimited']
        ];
    }

    public function checkRequest(): bool
    {
        if (!$this->checkCode($this->request['code'], 8) || !$this->getRequiredParams())  //TODO @length SMS code
            return false;

        $api = $this->sendJsonRequest(
            array(
                'apiKey' => $this->secret['api_key'],
                'apiSecret' => $this->secret['api_secret'],
                'number' => $this->request['number'],
                'code' => $this->request['code'],
                'unlimited' => $this->request['unlimited']
            ),
            'https://getpay.pl/panel/app/common/resource/ApiResource.php');
        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if (isset($api['infoCode']) && $api['infoCode'] == 200)
            return true;

        $this->setError($this->infoEnum[$api['infoCode']], $api['infoCode']);
        return false;
    }
}