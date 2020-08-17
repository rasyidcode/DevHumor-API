<?php

class InvalidPopularParamException extends Exception {

    public function __construct($message = '', $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}