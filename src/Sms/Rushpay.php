<?php

namespace bubanga\Sms;


use bubanga\PaymentException;

class Rushpay extends AbstractSms
{
    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key'],
            'request' => ['code', 'service_id']
        ];
    }

    public function checkRequest(): bool
    {
        if (!$this->checkCode($this->request['code'], 8) || !$this->getRequiredParams())
            return false;

        if (isset($this->request['products']) && is_array(($this->request['products']))) {
            $url = "https://rushpay.pl/api/v2/multi.php?userid=" . $this->secret['api_key'] . "&code=" . $this->request['code'] . '&serviceid=' . $this->request['service_id'];
        } else {
            $url = "https://rushpay.pl/api/v2/index.php?userid=" . $this->secret['api_key'] . "&code=" . $this->request['code'] . '&serviceid=' . $this->request['service_id'] . "&number=" . $this->request['number'];
        }

        $api = $this->sendGetRequest($url);

        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if (isset($api['data']['status']) && $api['data']['status'] == 1) {
            if (isset($this->request['products'][$api['data']['number']])) {
                $this->action($this->request['products'][$api['data']['number']]['action']);
            }

            return true;
        }

        $this->setError($api['data']['errorMessage'], $api['data']['errorCode']);
        return false;
    }
}