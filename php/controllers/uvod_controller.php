<?php


class uvod_controller extends base_controller {

    public function index_action($params)
    {
        $html = phpWrapperFromFile("php/controllers/uvod.php", $params);

        $this->render($html, $params);
    }
}