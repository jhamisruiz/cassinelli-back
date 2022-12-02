<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
//IMPORT::CONTROLERS('controllers', 'mercadopago');

class RouterBlanck
{
    //static functions...
}



//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    MercadoPago\SDK::setAccessToken(MP_ACCESS_TOKEN);
    $cards_name_for_status = array(
        "approved" => "APRO",
        "in_process" => "CONT",
        "call_for_auth" => "CALL",
        "not_founds" => "FUND",
        "expirated" => "EXPI",
        "form_error" => "FORM",
        "general_error" => "OTHE",
    );

    $i_current_month = intval(date('m'));
    $i_current_year = intval(date('Y'));

    $security_code = rand(111, 999);
    $expiration_month = rand($i_current_month, 12);
    $expiration_year = rand($i_current_year + 2, 2999);
    $dni = rand(11111111, 99999999);

    $payload = array(
        "json_data" => array(
            "card_number" => "5031433215406351",
            "security_code" => (string)$security_code,
            "expiration_month" => str_pad($expiration_month, 2, '0', STR_PAD_LEFT),
            "expiration_year" => str_pad($expiration_year, 4, '0', STR_PAD_LEFT),
            "cardholder" => array(
                "name" => $cards_name_for_status['approved'],
                "identification" => array(
                    "type" => "DNI",
                    "number" => (string)$dni
                )
            )
        )
    );

    $response = MercadoPago\SDK::post('/v1/card_tokens', $payload);
    //echo json_encode($response);
    $payment_methods = MercadoPago\SDK::get("/v1/payment_methods");
    //echo json_encode($payment_methods);

    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = (float)152.50;
    $payment->token = $response['body']['id'];
    $payment->description = 'pago de cuadros';
    $payment->installments = (int)1;
    $payment->payment_method_id = "master";
    //$payment->issuer_id = (int)$_POST['issuer'];
    // $payment->payer = array(
    //     "email" =>'jhamsel.raec@gmail.com'
    // );

    $payer = new MercadoPago\Payer();
    $payer->email = 'jhamsel.raec@gmail.com';
    $payer->identification = array(
        "type" => 'DNI',
        "number" => '47853698'
    );
    $payment->payer = $payer;

    $payment->save();

    $respo = array(
        'status' => $payment->status,
        'status_detail' => $payment->status_detail,
        'id' => $payment->id,
        'transaction_amount' => $payment->transaction_amount,
        'currency_id' => $payment->currency_id,
        'transaction_details' => $payment->transaction_details,
        'additional_info' => $payment->additional_info,
        'taxes' => $payment->taxes
    );
    echo json_encode($respo);
}
