<?php

namespace bubanga\Sms;


class Przelewy extends AbstractSms
{
    private function checkPrice ():bool
    {
        if (in_array($this->request['price'], [62, 123, 246, 369, 492, 615, 738, 861, 984, 1107, 1230, 1353, 1476, 1599, 1722, 1845, 1968, 2091, 2214, 2337, 2460, 3075]))
            return true;

        $this->setError(self::$error_code[5].'price: '.$this->request['price'], 5);
        return false;
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
        if (!$this->checkCode($this->request['code'], 6) || !$this->getRequiredParams() || !$this->checkPrice())
            return false;

        $url = "https://secure.przelewy24.pl/smsver.php?p24_id_sprzedawcy=" . $this->secret['api_key'] . "&p24_sms=" . $this->request['code'] . "&p24_kwota=" . $this->request['price'];
        $api = $this->sendGetRequest($url, false);

        if ($api == "OK")
            return true;

        $this->setError($api, explode(' ', $api)[1]);
        return false;
    }
}