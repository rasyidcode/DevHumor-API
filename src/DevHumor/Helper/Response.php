<?php

namespace DevHumor\Helper;

class Response {

    private $current_data;

    public function __construct(array $arrs) {
        $this->current_data = $arrs;
    }

    public static function create(array $arrs) {
        return new Response($arrs);
    }

    public function asJson() {
        header('Content-Type: application/json');
        return json_encode($this->current_data, JSON_PRETTY_PRINT);
    }

    public function asArray() {
        return $this->current_data;
    }

    public function asXML() {

    }

    public function asExcel() {

    }

    public function asCSV() {
        
    }

}