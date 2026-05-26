<?php

class App {
    protected $controller = 'PostController'; //vychozi metoda, kontroler a parametry
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();
//kontroler
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) { //ucfirst, prvni velke pismeno
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
//metoda
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
//parametry 
        $this->params = $url ? array_values($url) : [];
        //spusteni metody v kontroleru a predani parametru
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
//rozsekani url adresy
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}