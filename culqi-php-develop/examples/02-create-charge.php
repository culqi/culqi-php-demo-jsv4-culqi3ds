<?php
error_reporting(E_ALL & ~E_NOTICE);

header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


require '../Requests-master/library/Requests.php';
Requests::register_autoloader();
require '../lib/culqi.php';
include_once dirname(__DIR__) . '/../settings.php';

use Culqi\Culqi;

$culqi = new Culqi(array('api_key' => SECRET_KEY));
try {
  // Creando Cargo a una tarjeta

  $tds = array("authentication_3DS" => array(
    "eci" => $_POST["eci"],
    "xid" => $_POST["xid"],
    "cavv" => $_POST["cavv"],
    "protocolVersion" => $_POST["protocolVersion"],
    "directoryServerTransactionId" => $_POST["directoryServerTransactionId"]
  ));

  $req_body = array(
    "amount" => $_POST["amount"],
    "currency_code" => $_POST["currency_code"],
    "capture" => true,
    "email" => $_POST["email"],
    "source_id" => $_POST["token"],
    "description" => "PRUEBA BILLETERA 1",
    "antifraud_details" => array(
      "address" => "Andres Reyes 338",
      "address_city" => "Lima",
      "country_code" => "PE",
      "first_name" => "Roberto",
      "last_name" => "Beretta",
      "phone_number" => 123456789,
      "device_finger_print_id" => $_POST["deviceId"]
    ),
    "metadata" => array(
      "order_id" => "COD00001",
      "user_id" => "42052001",
      "sponsor" => "MiTienda"   //solo partners
    )
  );
  $with_tds = ($req_body) + (isset($_POST["xid"]) ? $tds : array());
  $charge = $culqi->Charges->create($with_tds);
  echo json_encode($charge);
} catch (Exception $e) {
  echo json_encode($e->getMessage());
}
