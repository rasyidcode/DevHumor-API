<?php

namespace DevHumor\Helper;

class Http {

    protected function curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($ch);
        
        curl_close($ch);

        return $res;
    }

    protected function curlPost() {

    }

}