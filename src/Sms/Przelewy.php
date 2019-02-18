<?php

namespace bubanga\Sms;


class Przelewy extends AbstractSms
{
    private function checkPrice () //TODO
    {

    }

    public function getRequiredParams():array
    {
        return [
            'secret' => ['api_key'],
            'request' => ['code', 'price']
        ];
    }

    public function checkRequest(): bool
    {
        if (!$this->checkCode($this->request['code'], 6) || !$this->getRequiredParams())
            return false;

        $url = "https://secure.przelewy24.pl/smsver.php?p24_id_sprzedawcy=" . $this->secret['api_key'] . "&p24_sms=" . $this->request['code'] . "&p24_kwota=" . $this->request['price'];
        $api = $this->sendGetRequest($url, false);

        if ($api == "OK")
            return true;

        $this->setError($api, explode(' ', $api)[1]);
        return false;
    }
}