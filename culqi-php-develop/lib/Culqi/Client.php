<?php

namespace Culqi;

use Culqi\Error as Errors;

/**
 * Class Client
 *
 * @package Culqi
 */
class Client
{

  public function request($method, $url, $api_key, $data = NULL, $secure_url = false)
  {
    try {

      $headers = array("Authorization" => "Bearer " . $api_key, "Content-Type" => "application/json", "Accept" => "application/json");

      $options = array(
        'timeout' => 180
      );

      $base_url = $secure_url ?  Culqi::SECURE_BASE_URL : Culqi::BASE_URL;

      if ($method == "GET") {
        $url_params = is_array($data) ? '?' . http_build_query($data) : '';
        $response = \Requests::get($base_url . $url . $url_params, $headers, $options);
      } else if ($method == "POST") {
        $response = \Requests::post($base_url . $url, $headers, json_encode($data), $options);
      } else if ($method == "PATCH") {
        $response = \Requests::patch($base_url . $url, $headers, json_encode($data), $options);
      } else if ($method == "DELETE") {
        $response = \Requests::delete($base_url, $options);
      }
    } catch (\Exception $e) {
      throw new Errors\UnableToConnect();
    }
    if ($response->status_code >= 200 && $response->status_code <= 206) {
      if ($method == "DELETE") {
        return $response->status_code == 204 || $response->status_code == 200;
      }
      return json_decode($response->body);
    }
    if ($response->status_code == 400) {
      throw new Errors\UnhandledError($response->body, $response->status_code);
    }
    if ($response->status_code == 401) {
      throw new Errors\AuthenticationError();
    }
    if ($response->status_code == 404) {
      throw new Errors\NotFound();
    }
    if ($response->status_code == 403) {
      throw new Errors\InvalidApiKey();
    }
    if ($response->status_code == 405) {
      throw new Errors\MethodNotAllowed();
    }
    throw new Errors\UnhandledError($response->body, $response->status_code);
  }
}
