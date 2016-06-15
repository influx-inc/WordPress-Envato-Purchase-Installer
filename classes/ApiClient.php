<?php
class ApiClient {
  const API_ENDPOINT = 'https://api.envato.com/v3/market/buyer/%s';

  private $api_key;
  private $config;

  public function __construct($config) {
    $this->config  = $config;
    $this->api_key = $config->get_api_key();
  }

  public function get_purchases() {
    $purchases = $this->config->get_cached_purchases();

    if (!$purchases) {
      $purchases = array();

      foreach ($this->do_curl('list-purchases')['results'] as $purchase) {
        $purchases[] = array(
          'id'   => $purchase['item']['id'],
          'type' => $this->get_type($purchase),
          'name' => $purchase['item']['name'],
        );
      }

      return $this->config->set_cached_purchases($purchases);
    }

    return $purchases;
  }

  public function get_download_url($id) {
    return $this->do_curl('download', array('item_id' => $id))['wordpress_theme'];
  }

  private function do_curl($resource, $params = null) {
    $api_endoint = self::API_ENDPOINT;

    if ($params) {
      $api_endoint .= '?' . http_build_query($params);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, sprintf($api_endoint, $resource));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->api_key));

    $output = curl_exec($ch);

    curl_close($ch);

    return json_decode($output, true);
  }

  private function get_type($purchase) {
    return $purchase['item']['site'] == 'themeforest.net' ? 'theme' : 'plugin';
  }
}
