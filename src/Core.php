<?php

namespace Crawler;

class Core
{
    private string $currentController = 'Controllers\Articles';
    private string $currentMethod = 'index';
    private $params = [];

    public function __construct() {
        $url = $this->getUrl();

        if(file_exists('../controllers/' . ucwords($url[0]). '.php')){
            // If exists, set as controller
            $this->currentController = 'Controllers\\' . ucwords($url[0]);
            unset($url[0]);
        }

        if(isset($url[1])){
            // Check to see if method exists in controller
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        $obj = new $this->currentController;
        call_user_func_array([$obj, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if(isset($_SERVER['REQUEST_URI'])){
            $url = trim($_SERVER['REQUEST_URI'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return 'No url ';
    }
}