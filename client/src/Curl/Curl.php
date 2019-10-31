<?php

namespace myproject\Curl;

use myproject\traits\Singleton;

/**
 * See https://gist.github.com/surferxo3/522e9882e9f00b47de8e72c553232c05.
 */
class Curl {

  use Singleton;

  public function get(string $url) : array {
    $reqBody = '';
    $headers = array();
    list($header, $body) = $this->initCurlRequest('GET', $url, $reqBody, $headers);

    return [
      'h' => $header,
      'b' => $body,
    ];
  }

  function initCurlRequest($reqType, $reqURL, $reqBody = '', $headers = array()) {
      if (!in_array($reqType, array('GET', 'POST', 'PUT', 'DELETE'))) {
          throw new Exception('Curl first parameter must be "GET", "POST", "PUT" or "DELETE"');
      }
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $reqURL);
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $reqType);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $reqBody);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_HEADER, true);

     	$body = curl_exec($ch);
     	// extract header
     	$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  	$header = substr($body, 0, $headerSize);
  	$header = $this->getHeaders($header);
  	// extract body
  	$body = substr($body, $headerSize);
      curl_close($ch);

      return [$header, $body];
  }
  function getHeaders($respHeaders) {
      $headers = array();
      $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));
      foreach (explode("\r\n", $headerText) as $i => $line) {
          if ($i === 0) {
              $headers['http_code'] = $line;
          } else {
              list ($key, $value) = explode(': ', $line);
              $headers[$key] = $value;
          }
      }
      return $headers;
  }

}
