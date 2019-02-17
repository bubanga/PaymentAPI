<?php

namespace bubanga\Sms;


class SimPay extends AbstractSms
{
    use SmsTrait;

    protected function checkRequest ():bool
    {
        $url = 'https://simpay.pl/api/status';
        $data = $this->generateData();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // developer only

        $call = curl_exec($curl);
        $response = json_decode($call, true);
        $error = curl_errno($curl);

        curl_close($curl);

        if ($error > 0) {
            throw new RuntimeException('CURL ERROR Code:'.$error);
        }

        return $this->response = $response;
    }

    private function generateData ():string
    {
        if (!$this->checkCode($this->request['code'])) {
            //TODO Error
        }

        return json_encode(array('params'=>array_merge(['auth' => $this->secret], $this->request)));
    }
}