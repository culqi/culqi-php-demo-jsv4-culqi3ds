<?php
/**
 * Ejemplo 1
 * Como crear un token a una tarjeta Culqi PHP.
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

try {
  // Usando Composer (o puedes incluir las dependencias manualmente)
   require '../Requests-master/library/Requests.php';
  Requests::register_autoloader();
  require '../lib/culqi.php';
  include_once dirname(__DIR__) . '/../settings.php';

  $culqi = new Culqi\Culqi(array('api_key' => SECRET_KEY));

  // Creando Cargo a una tarjeta
  $token = $culqi->Tokens->create(
      array(
        "card_number" => "4111111111111111",
        "cvv" => "123",
        "email" => "wmuro".uniqid()."@me.com", //email must not repeated
        "expiration_month" => 9,
        "expiration_year" => 2020,
        "fingerprint" => uniqid()
      )
  );
  // Respuesta
  echo json_encode("Token: ".$token->id);

} catch (Exception $e) {
  echo json_encode($e->getMessage());
}
