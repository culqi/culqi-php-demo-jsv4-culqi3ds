<?php
error_reporting(E_ALL & ~E_NOTICE);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/**
 * Crear un charge a una tarjeta usando Culqi PHP.
 */

try {
  // Cargamos Requests y Culqi PHP
  require '../Requests-master/library/Requests.php';
  Requests::register_autoloader();
  require '../lib/culqi.php';
  include_once dirname(__DIR__) . '/../settings.php';

  $culqi = new Culqi\Culqi(array('api_key' => SECRET_KEY));
  
  // Creando Cargo a una tarjeta
  $order = $culqi->Orders->create(
    array(
      "amount" => 1100,
      "currency_code" => "PEN",
      "description" => 'Prueba Orden 1',
      "order_number" => "#id-" . rand(1, 9999),
      "client_details" => array(
        "first_name" => "Jordan",
        "last_name" => "Orden",
        "email" => EMAIL_CUSTOMER,
        "phone_number" => "999145221"
      ),
      "expiration_date" => time() + 24 * 60 * 60,
      "confirm" => false,
      // Orden con (01) dia de validez (hora-min-seg)

    )
  );
  // Respuesta
  echo json_encode($order);
} catch (Exception $e) {
  error_log($e->getMessage());
  echo $e->getMessage();
}
