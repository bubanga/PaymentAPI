<?php

namespace bubanga\Sms;


class Microsms extends AbstractSms
{
    use SmsTrait;

    public function checkRequest(): bool
    {
        if (isset($this->request['products'])) {
            $products = true;
            $url = "http://microsms.pl/api/v2/multi.php?userid=" . $this->secret['userid'] . "&code=" . $this->request['code'] . '&serviceid=' . $this->secret['serviceid'];
        } else {
            $products = false;
            $url = "http://microsms.pl/api/v2/multi.php?userid=" . $this->secret['userid'] . "&code=" . $this->request['code'] . '&serviceid=' . $this->secret['serviceid'] . "&number=" . $this->request['number'];
        }

        $api = @file_get_contents($url);
        $api = json_decode($api, true);

        if (isset($api['data']['status']) && $api['data']['status'] == 1) {
            if ($products && isset($this->request['products'][$api['data']['number']])) {
                $action = $this->request['products'][$api['data']['number']]['action'];
                if (is_array($action) && isset($action[2])) {
                    call_user_func_array(array($action[0], $action[1]), $action[2]);
                } else {
                    call_user_func($action[0], $action[1]);
                }
            }
            return true;
        }
    }
}