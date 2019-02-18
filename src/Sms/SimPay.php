<?php

namespace bubanga\Sms;


class SimPay extends AbstractSms
{
    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key', 'api_secret'],
            'request' => ['code', 'number', 'service_id']
        ];
    }

    public function checkRequest ():bool
    {
        if (!$this->checkCode($this->request['code'], 6) || !$this->getRequiredParams())
            return false;

        $api = $this->sendJsonRequest(
            array(
                'params' => array_merge(
                    ['auth' => $this->secret],
                    $this->request
                )
            ),
            'https://simpay.pl/api/status');
        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if(isset($api['respond']['status']) && $api['respond']['status'] == 'OK')
            return true;


        $this->setError($api['error']['error_name'], $api['error']['error_code']);
        return false;
    }
}