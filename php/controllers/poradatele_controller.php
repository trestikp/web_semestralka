<?php

class poradatele_controller extends base_controller {

    public function index_action($params)
    {
        $html = phpWrapperFromFile("php/controllers/poradatele.php", $params);

        $this->render($html, $params);
    }
}