<?php
    error_reporting(E_ALL);

    ini_set('error_reporting', E_ALL);
    ini_set("display_errors", 1);

    require_once ('../vendor/autoload.php');

    use bubanga\Payment;

    $payment = new Payment();

    $secretPSC = array('api_key' => "bdbbc6b4a6a65ff9b30768ea6da61615", 'api_secret' => "wckpVPDCSiCg");
    $requestPSC = array ();
    $responsePSC = array(
        'KWOTA' => 10,
        'ID_ZAMOWIENIA' => 1,
        'STATUS' => 'SUCCESS',
        "SEKRET" => "Z3VuNVhSdFFtRWhWSjVibE1xNkxuU24rVmY2SzhmUy9MelNxWmVmaEdqND0,", "HASH" => "2ba5171b72f89fdada11a0c5623464acb24b5e8d234038e51c647a8ddbfe2799");

    $payment->setPaymentPSC(new \bubanga\Paysafecard\Hotpay(), $secretPSC, $requestPSC, $responsePSC);


    if (isset($_POST) && $_POST && isset($_GET['check'])) {

        if ($payment->getPaymentPSC()->getResult()) {
            echo "Success";
        } else {
            $error = $payment->getPaymentPSC()->getError();
            echo $error['number'] . " " . $error['text'];
        }

    } else {
        $secretPSC = array('api_key' => "", 'api_secret' => "wckpVPDCSiCg");
        $requestPSC = array (
            'price' => 10,
            'shop_id' => 1,
            'secret' => "Z3VuNVhSdFFtRWhWSjVibE1xNkxuU24rVmY2SzhmUy9MelNxWmVmaEdqND0",
            'name' => "Test",
            'email' => "kubuspl@onet.eu",
            'data' => "",
            'response' => "http://www.mollecms.pl/payment/test/"
        );

        $payment->setPaymentPSC(new \bubanga\Paysafecard\Hotpay(), $secretPSC, $requestPSC);

        /**
         * Z tych danych tworzy sie formularz POST
         */
        $data = $payment->getPaymentPSC()->getData();
        $url = $payment->getPaymentPSC()->getData(true)[0];

        $form = '<form method="post" action="'.$url.'">';
        foreach ($data as $name => $value) {
            $form .= '<input type="text" name="'.$name.'" value="'.$value.'">';
        }

        $form .= '<input type="submit" value="Kup za '.$requestPSC['price'].' PLN"> </form>';

        echo $form;
    }
