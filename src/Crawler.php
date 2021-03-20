<?php

namespace Crawler;

class Crawler {
    private string $response;

    public function __construct() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.15min.lt/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->response = curl_exec($ch);
    }

    public function getResponse() {
        return $this->response;
    }
}
