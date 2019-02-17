<?php

namespace bubanga\Sms;


abstract class AbstractSms
{
    abstract protected function checkCode ();

    abstract public function setSecret (array $secret);

    abstract public function setRequest (array $request);

    abstract public function getResponse():bool;

    abstract protected function checkRequest():bool;

}