<?php

class Route {

    private static $valid_routes = array();

    public static function get($route, $function) {
        self::$valid_routes[]   = $route;

        $url    = $_GET['url'];
        print_r($url);die();

        if ($_GET['url'] == $route) {
            $function->__invoke();
        }
    }

}