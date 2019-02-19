<?php

namespace bubanga\Sms;


use bubanga\PaymentException;

abstract class AbstractSms
{
    protected $error;
    protected $response;
    protected $secret;
    protected $request;
    protected $action;

    static protected $error_code = [
        0 => "[System] Unidentified",
        1 => "[System] Your code is bad because it does not meet the requirements",
        2 => "[System] Your code is incorrect",
        3 => "[System] Error system API",
        4 => "[System] Not found value for "
    ];

    abstract public function getRequiredParams():array;

    abstract public function checkRequest():bool;

    protected function checkRequiredParams ():bool
    {
        foreach ($this->getRequiredParams() as $param => $item) {
            $value = $this->$param;
            if (!isset($value[$item])) {
                $this->setError(self::$error_code[4] . $param . " => " . $item, 4);
                return false;
            }
        }

        return true;
    }

    protected function checkCode ($code, int $length = 6):bool
    {
        $code = addslashes($code);

        if (preg_match("/^[A-Za-z0-9]{".$length."}$/", $code)) {
            return true;
        }

        $this->setError(self::$error_code[1], 1);
        return false;
    }

    public function setSecret (array $secret):void
    {
        $this->secret = $secret;
    }

    public function setRequest (array $request):void
    {
        $this->request = $request;
    }

    public function setAction(array $action):void
    {
        $this->action = $action;
    }

    public function getResult():bool
    {
        return $this->checkRequest();
    }

    public function getResponse ():?array
    {
        return $this->response;
    }

    public function getError():?array
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

    protected function sendJsonRequest(array $value, string $website):?array
    {
        $bodyJSON = json_encode($value);

        $options = array(
            'http' => array(
                'header' => 'Content-Type: application/json',
                'method' => 'POST',
                'content' => $bodyJSON
            )
        );

        $context = stream_context_create($options);
        $api = @file_get_contents(
            $website,
            FALSE,
            $context);

        if (($result = json_decode($api, true))) {
            $this->response = $result;
            return $result;
        }
    }

    protected function sendGetRequest(string $website, bool $decode = true):?array
    {
        $api = @file_get_contents($website);
        if (!$decode) {
            $this->response = array($api);
            return array($api);
        }


        if (($result = json_decode($api, true))) {
            $this->response = $result;
            return $result;
        }

    }

    protected function action(array $action):void
    {//TODO catch
        try {
            if (isset($action['func'])) {
                call_user_func($action['func'], $action['params']);
            } else {
                call_user_func_array(array($action['class'], $action['method']), $action['params']);
            }
        } catch (PaymentException $exception)
        {

        }
    }

    public function isAction ():bool
    {
        if (isset($this->action) && is_array($this->action))
            return true;

        return false;
    }
}