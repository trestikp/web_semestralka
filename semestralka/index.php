<?php
include ("php/functions.inc.php");
include ("php/users.class.php");

$page = $_REQUEST["page"];
$fileload = "";
$prihlasen = false;

if($page == "") {
    $page = "uvod";
}
else if($page == "uvod") $fileload = "uvod.php";
else if($page == "temata") $fileload = "temata.php";
else if($page == "poradatele") $fileload = "poradatele.php";
else if($page == "pravidla") $fileload = "pravidla.php";
else echo "Error loading file!";

$obsah = phpWrapperFromFile($fileload);

$pages = array();
$pages["uvod"] = "Úvod";
$pages["temata"] = "Témata";
$pages["poradatele"] = "Pořadatelé";
$pages["pravidla"] = "Pravidla příspěvků";

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

//prihlasovani
$login_error = "";
$is_successful = false;
//include ("php/login_form.php");

if(!$prihlasen){
    $log_from = "<form action=\"index.php?page=$page\" method=\"post\" onsubmit=\"\">
                <dl>
                    <dt>Username</dt>
                    <dd><input type=\"text\" name=\"username\"></dd>
                    <dt>Password</dt>
                    <dd><input type=\"password\" name=\"password\"></dd>
                    <dd><input type=\"submit\" name=\"login\" value=\"Přihlásit\"></dd>
                </dl>
            </form>";
} else {
    $log_from = "<p>Přihlášený uživatel --jmeno--</p><a>Odhlásit</a>";
}

if(isset($_POST["login"])){

    if(!empty($_POST["username"])) {
        $username = $_POST["username"];

        if (!empty($_POST["password"])){
            $password = $_POST["password"];

            $temp = new users();
            if(!($temp->load_usr($username))){
                $login_error = "Uživatel neexistuje.";
            }
            else
            if($temp->login($username, $password))
                $prihlasen = true;
            else $login_error = "Špatné jméno nebo heslo";
            //$temp->get_usr();
        }
        else $login_error = "Please enter password.";
    }
    else $login_error = "Please enter username.";
}

if($prihlasen) echo "jsem prihlaseny";

require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('not_logged_usr.html');
$template_params = array();
$template_params["nav"] = $navs;
$template_params["obsah"] = $obsah;
if($login_error != "")
    $template_params["log_error"] = $login_error;
$template_params["log_form"] = $log_from;
echo $template->render($template_params);