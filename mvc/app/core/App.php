<?php
class App
{
  protected $controller = 'Home';
  protected $method = 'index';
  protected $params = [];

  public function __construct()
  {
    $url = $this->parseURL();

    // Controller
    if (!empty($url[0]) && file_exists('../app/controllers/' . $url[0] . '.php')) {
      $this->controller = $url[0];
      unset($url[0]);
    }

    require_once '../app/controllers/' . $this->controller . '.php';
    $this->controller = new $this->controller;

    // Method
    if (isset($url[1])) {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    // Params
    $this->params = $url ? array_values($url) : [];

    try {
      call_user_func_array([$this->controller, $this->method], $this->params);
    } catch (Exception $e) {
      error_log($e->getMessage());
      echo "Terjadi kesalahan. Silakan coba lagi nanti.";
    }
  }

  protected function parseURL()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      return explode('/', $url);
    }
    return [];
  }
}
