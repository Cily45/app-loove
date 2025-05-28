<?php

namespace App;

use App\Core\Request;
use App\Core\Routeur;

class Kernel {

    public function __construct(
        private Routeur $routeur
    ) {
        $this->run();
    }

    private function run() {
        $request = new Request($_SERVER, $_GET, $_POST);
        $response = $this->routeur->request($request);
        
        echo $response->getBody();
    }
}