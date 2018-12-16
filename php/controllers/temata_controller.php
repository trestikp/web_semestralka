<?php

class temata_controller extends base_controller {
    public function index_action($params){
        $html = phpWrapperFromFile("php/controllers/temata.php", $params);

        $this->render($html, $params);
    }
}