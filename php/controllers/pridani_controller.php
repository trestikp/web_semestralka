<?php

class pridani_controller extends base_controller{

    public function index_action($params){
        $html = phpWrapperFromFile("php/controllers/pridani.php", $params);

        $this->render($html, $params);
    }
}