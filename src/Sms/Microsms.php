<?php

namespace bubanga\Sms;


class Microsms extends AbstractSms
{
    private function action(array $action)
    {//TODO catch
        try {
            if (isset($action['type']) && $action['type'] == true) {
                call_user_func_array(array($action['class'], $action['method']), $action['params']);
            } else {
                call_user_func($action['func'], $action['params']);
            }
        } catch (PaymentException $exception)
        {

        }
    }

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

        $products = true;
        $url = "http://microsms.pl/api/v2/multi.php?userid=" . $this->secret['api_key'] . "&code=" . $this->request['code'] . '&serviceid=' . $this->request['service_id'];

        if (!isset($this->request['products'])) {
            $products = false;
            $url .= "&number=" . $this->request['number'];
        }

        $api = $this->sendGetRequest($url);
        if (!is_array($api)) {
            $this->setError(self::$error_code[3], 3);
            return false;
        }

        if (isset($api['data']['status']) && $api['data']['status'] == 1) {
            if ($products && isset($this->request['products'][$api['data']['number']])) {
                $this->action($this->request['products'][$api['data']['number']]['action']);
            }

            return true;
        }

        $this->setError($api['data']['errorMessage'], $api['data']['errorCode']);
        return false;
    }
}