<?php

class Converter {

    public static function asJson() {
        header('Content-Type: application/json');
        return json_encode(self::$current_data);
    }

    public static function asArray() {
        return self::$current_data;
    }

    public static function asXML() {

    }

    public static function asExcel() {

    }

    public static function asCSV() {
        
    }

}