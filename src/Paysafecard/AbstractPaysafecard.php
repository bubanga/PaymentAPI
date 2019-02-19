<?php

namespace bubanga\Paysafecard;


abstract class AbstractPaysafecard
{
    protected $secret;
    protected $request;
    protected $action;
    protected $response;
    protected $error;

    static protected $error_code = [
        0 => "[System] Unidentified",
        1 => "[System] Not authorization",
        2 => "[System] Not found value for "
    ];

    public function setSecret (array $secret)
    {
        /**
         * api_key
         * api_secret
         */

        $this->secret = $secret;
    }

    public  function setRequest(array $request)
    {
        /**
         * shop_id
         * price [amount]
         * response
         * return
         *  success
         *  fail
         *
         */

        $this->request = $request;
    }

    public function setAction (array $action)
    {
        $this->action = $action;
    }

    abstract public function checkResponse(bool $authorization = true);

    abstract public function getData(bool $provider = false):?array;

    abstract public function requestAuthorization():bool;

    abstract public function getRequiredParams(bool $response = false):array;

    public function getResponse ():array
    {
        return $this->response;
    }

    public function setResponse (array $response)
    {
        $this->response = $response;
    }

    public function getResult ():bool
    {
        return $this->checkResponse();
    }

    public function getError ():?array
    {
        return $this->error;
    }

    protected function setError(string $text, int $number = 0):void
    {
        $this->error = [
            'number' => $number,
            'text' => $text
        ];
    }

    protected function checkRequiredParams(bool $response = false):bool
    {
        $params[] = 'secret';
        $params[] = 'request';
        foreach ($params as $param)
        {
            if (isset($this->getRequiredParams($response)[$param])) {
                foreach ($this->getRequiredParams($response)[$param] as $item) {
                    $value = $this->$param;

                    if (!isset($value[$item])) {
                        $this->setError(self::$error_code[2] . $param . " => " . $item, 2);
                        return false;
                    }
                }
            }
        }

        return true;
    }
}