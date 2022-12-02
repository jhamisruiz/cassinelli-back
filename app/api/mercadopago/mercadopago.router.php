<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');
//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
//IMPORT::CONTROLERS('controllers', 'mercadopago');

class RouterPMpago
{
    //static functions...
    public function pago()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        if (isset($data['data']) && isset($data['adicional'])) {
            MercadoPago\SDK::setAccessToken(MP_ACCESS_TOKEN);
            // Crea un objeto de preferencia
            $preference = new MercadoPago\Preference();
            $products = array();

            # Building an item 
            $cuadros = $data['data'];

            $add = $data['adicional'];
            for ($i = 0; $i <= count($add); $i++) {
                if ($i == 0) {
                    $item = new MercadoPago\Item();
                    $item->id = "00010";
                    $item->title = $cuadros['name'];
                    $item->quantity = 1;
                    $item->unit_price = $cuadros['total'];
                    $item->currency_id = 'PEN';
                    $products[] = $item;
                }
                if ($i > 0) {
                    if ($add[($i - 1)]['quantity']) {
                        $item2 = new MercadoPago\Item();
                        $item2->id = "0000" . $add[($i - 1)]['id'];
                        $item2->title = $add[($i - 1)]['name'];
                        $item2->quantity = $add[($i - 1)]['quantity'];
                        $item2->unit_price = $add[($i - 1)]['price'];
                        $item2->currency_id = 'PEN';
                        $products[] = $item2;
                    }
                }
            }
            //REQUEST::RESPONDER($products, 200);
            $preference->items = $products;



            $preference->back_urls = [
                'success' => FRONT_URL . '/preview?success=success',
                'failure' => FRONT_URL . '/preview?error=failure',
                'pending' => FRONT_URL . '/preview?error=pending',
            ];
            $preference->auto_return = 'approved';
            $preference->binary_mode = true;

            $preference->save();
            //echo json_encode([$preference->sandbox_init_point, $preference->init_point]);
            REQUEST::RESPONDER([$preference->sandbox_init_point, $preference->init_point], 200);
        }
        Errors::__Log('Error al cargar mercado pago', 200);
    }
}



//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    $pago = new RouterPMpago();
    $pago->pago();
}
