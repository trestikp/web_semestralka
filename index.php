<?php
/**
 * Basic functions include
 */
include("php/inc/functions.inc.php");
include("php/inc/users.class.php");

/**
 * Twig loader
 */
require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

/**
 * Page getting
 */
if(isset($_REQUEST["page"])){
    $page = $_REQUEST["page"];
} else {
    $page = "uvod";
}

/**
 * List of pages
 */
$pages = array();
$pages["uvod"] = "Úvod";
$pages["temata"] = "Témata";
$pages["poradatele"] = "Pořadatelé";
$pages["pravidla"] = "Pravidla příspěvků";
$additional_pages["pridani"] = "Přidání příspěvku";

/**
 * Adding pages to params for twig
 */
$params = array();
$params["page"] = $page;
$params["pages"] = $pages;

/**
 * Navigation
 */
$navs = "";
$navs .= "<ul>\n";
if($pages != null){
    foreach($pages as $key => $title){
        if($page == $key) $is_active = "active";
        else $is_active = "";
        $navs .= "<li class='nav-item list-unstyled'><a class='nav-link $is_active' href='index.php?page=$key'>$title</a></li>\n";
    }
}
$navs .= "</ul>";


/**
 * Login form and action
 */
session_start();

$login_error = "";
$prihlasen = false;
$is_successful = false;
$action = @$_REQUEST["action"];


    $log_from = "<form action=\"index.php?page=$page\" method=\"post\" onsubmit=\"\">
                <dl>
                    <dt>Username</dt>
                    <dd><input type=\"text\" name=\"username\"></dd>
                    <dt>Password</dt>
                    <dd><input type=\"password\" name=\"password\"></dd>
                    <dd><input type=\"submit\" name=\"login\" value=\"Přihlásit\"></dd>
                </dl>
            </form>";



if(isset($_POST["login"])){

    if(!empty($_POST["username"])) {
        $username = $_POST["username"];

        if (!empty($_POST["password"])){
            $password = $_POST["password"];

            if(!($temp = new users($username))){
                $login_error = "Uživatel neexistuje.";
            }
            else
            if($temp->login($username, $password)) {
                $prihlasen = true;
                $_SESSION["user_id"] = $username;
            }
            else $login_error = "Špatné jméno nebo heslo";
            //$temp->get_usr();
        }
        else $login_error = "Please enter password.";
    }
    else $login_error = "Please enter username.";
}

if(isset($_SESSION["user_id"])){
    echo "session active";
    $log_from = "<p>Přihlášený uživatel <a>".$_SESSION["user_id"]."</a></p><a href=\"index.php?action=logout\">Odhlásit</a>";
    $_SESSION["data"] = new users($_SESSION["user_id"]);

    if($_SESSION["data"]->get_role() == "autor"){
        $pages["pridani"] = $additional_pages["pridani"];
        if($page == "pridani") $is_active = "active";
        else $is_active = "";
        $navs = substr($navs, 0, strlen($navs)-5);
        $navs .= "<li class='nav-item list-unstyled'><a class='nav-link $is_active' href='index.php?page=pridani'>".$pages["pridani"]."</a></li>\n";
        $navs .= "</ul>";
    }
}

if($action == "logout"){
    $_SESSION["user_id"] = array();
    unset($_SESSION["user_id"]);
    session_destroy();
}

/**
 * Adding nav and login form to param for twig
 */
$params["nav"] = $navs;
$params["log_form"] = $log_from;

/**
 * Page switcher (nav "controller");
 */
include_once("php/controllers/base_controller.php");

if(array_key_exists($page, $pages)){
    $ctrl_name = $page."_controller";
} else {
    $ctrl_name = "error_controller";
}

$filename_ctrl = "php/controllers/$ctrl_name.php";

if(file_exists($filename_ctrl) && !is_dir($filename_ctrl)){
    include_once($filename_ctrl);

    $$ctrl_name = new $ctrl_name($twig);
    $$ctrl_name->index_action($params);
} else {
    echo "Chyba při includování controlleru $ctrl_name";
}