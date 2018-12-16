<?php

class error_controller extends  base_controller {

    public function index_action($params){
        $html = phpWrapperFromFile("error.php", $params);

        $pages = $params["pages"];

        $this->render($html, $pages);
    }
}