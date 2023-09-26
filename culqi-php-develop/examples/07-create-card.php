<?php
/**
 * Ejemplo 2
 * Como crear un charge a una tarjeta usando Culqi PHP.
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

try {
  // Usando Composer (o puedes incluir las dependencias manualmente)
  require '../vendor/autoload.php';
  include_once dirname(__DIR__) . '/../settings.php';

  $culqi = new Culqi\Culqi(array('api_key' => SECRET_KEY));

  // Creando Cargo a una tarjeta
  $card = $culqi->Cards->create(
    array(
      "customer_id" => "{customer_id}",
      "token_id" => "{token_id}"
    )
  );
  // Respuesta
  echo json_encode($card);

} catch (Exception $e) {
  echo json_encode($e->getMessage());
}
