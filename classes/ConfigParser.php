<?php
class ConfigParser {
  private $config = array();
  private $config_file;

  public function __construct($config_file) {
    $this->config_file = $config_file;
    $this->load_config();
  }

  public function get_api_key() {
    if (!$this->config['envato_api_key']) {
      do {
        $this->config['envato_api_key'] = readline("Envato API Key: ");
        $this->save_config();
      } while($this->config['envato_api_key'] == null);
    }
    return $this->config['envato_api_key'];
  }

  public function get_cached_purchases() {
    if ($this->config['cached_purchases'] && time() < $this->config['cache_expiry_time']) {
      return $this->config['cached_purchases'];
    }
  }

  public function set_cached_purchases($purchases) {
    $this->config['cached_purchases'] = $purchases;
    $this->config['cache_expiry_time'] = time() + 3600;
    $this->save_config();

    return $purchases;
  }

  private function load_config() {
    if (file_exists($this->config_file)) {
      $this->config = json_decode(file_get_contents($this->config_file), true);
    }
  }

  private function save_config() {
    file_put_contents($this->config_file, json_encode($this->config));
  }
}
