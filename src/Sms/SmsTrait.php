<?php

namespace bubanga\Sms;


trait SmsTrait
{
    private $secret;
    private $request;
    private $response;

    protected function checkCode ($code)
    {
        $code = addslashes($code);

        if (preg_match("/^[A-Za-z0-9]{8}$/", $code)) {
            return true;
        }

        return false;
    }

    public function setSecret (array $secret)
    {
        $this->secret = $secret;
    }

    public function setRequest (array $request)
    {
        $this->request = $request;
    }

    public function getResponse():bool
    {
        $this->checkRequest();
        return $this->response;
    }
}