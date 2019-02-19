<?php
    error_reporting(E_ALL);

    ini_set('error_reporting', E_ALL);
    ini_set("display_errors", 1);

    require_once ('../vendor/autoload.php');

    use bubanga\Payment;

    $payment = new Payment();

    if (isset($_POST) && $_POST && isset($_GET['check'])) {
        $secretPSC = array('api_key' => "", 'api_secret' => "");
        $requestPSC = array ('price' => "", 'shop_id' => "");

        $payment->setPaymentPSC(new \bubanga\Paysafecard\Rushpay(), $secretPSC, $requestPSC, $_POST);

        if ($payment->getPaymentPSC()->getResult()) {
            echo "Success";
        } else {
            $error = $payment->getPaymentPSC()->getError();
            echo $error['number'] . " " . $error['text'];
        }


    } else {
        $secretPSC = array('api_key' => 999, 'api_secret' => "xxxxxx");
        $requestPSC = array ('price' => 12.5, 'shop_id' => 998, 'return_ok' => $_SERVER["REQUEST_URI"].'?success', 'return_fail' => $_SERVER["REQUEST_URI"].'?fail', 'response' => $_SERVER["REQUEST_URI"].'?check');

        $payment->setPaymentPSC(new \bubanga\Paysafecard\Rushpay(), $secretPSC, $requestPSC);

        /**
         * Z tych danych tworzy sie formularz POST
         */
        $data = $payment->getPaymentPSC()->getData();
        $url = $payment->getPaymentPSC()->getData(true)[0];

        $form = '<form method="post" action="'.$url.'">';
        foreach ($data as $name => $value) {
            $form .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
        }

        $form .= '<input type="submit" value="Kup za '.$requestPSC['price'].' PLN"> </form>';

        echo $form;
    }