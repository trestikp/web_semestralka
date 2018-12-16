<?php

class base_controller {
    protected $twig;

    public function __construct($twig){
        $this->twig = $twig;
    }

    public function render($obsah, $params = null){
        echo $this->twig->render("main_template.html", array("obsah" => $obsah, "pages" => $params["pages"],
            "nav" => $params["nav"], "log_form" => $params["log_form"]));
    }

    public function index_action($params){
        if($params) echo "Missing parameters";
    }
}