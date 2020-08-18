<?php

namespace DevHumor\Exception;

class NotFoundElementException extends \Exception {

    public function __construct() {
        parent::__construct('Something wrong occured. Patch needed for this.', 500, null);
    }

}