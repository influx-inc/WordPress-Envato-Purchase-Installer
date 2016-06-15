<?php
class CliParser {
  private $api;

  public function __construct($api) {
    $this->api = $api;
  }

  public function list_purchases($item = null) {
    foreach ($this->api->get_purchases() as $purchase) {
      if ($item && $item != $purchase['type'] . 's') {
        continue;
      }

      $this->print_purchase($purchase);
    }
  }

  public function find_purchases($name) {
    foreach ($this->api->get_purchases() as $purchase) {
      if (strpos(strtolower($purchase['name']), $name) !== false) {
        $this->print_purchase($purchase);
      }
    }
  }

  public function show_purchase($name) {
    $purchase = $this->api->find_purchase($name);

    if (!$purchase) die("{$name} not found" . PHP_EOL);

    die("{$purchase['type']}\t{$purchase['name']}" . PHP_EOL);
  }

  public function install_purchase($id) {
    $purchase = null;

    foreach ($this->api->get_purchases() as $p) {
      if ($p['id'] == $id) {
        $purchase = $p;
        break;
      }
    }

    if (!$purchase) {
      die("item with id {$id} not found" . PHP_EOL);
    }

    $download_url = $this->api->get_download_url($id);
    $parts        = explode('/', parse_url($download_url)['path']);
    $zip          = array_pop($parts);

    echo exec(sprintf('wget -O %s "%s"', $zip, $download_url)) . PHP_EOL;
    echo exec(sprintf('unzip -o -d %ss %s', $purchase['type'], $zip)) . PHP_EOL;

    return $purchase;
  }

  private function print_purchase($purchase) {
    $line = printf(
      '%-10s %-8s %s',
      $purchase['id'],
      $purchase['type'],
      $purchase['name']
    );

    echo $line . PHP_EOL;
  }
}
