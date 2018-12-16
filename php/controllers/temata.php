<?php
include ("temata.class.php");
echo "<h3>Vyber si téma</h3><hr>";

$temata = new temata();
$themes =  $temata->get_themes();

echo "\n<table class='table table-striped'>\n<tbody>\n";
foreach ($themes as $item){
    echo "<tr><th scope='row'><a href='#'>$item</a></th></tr>\n";
}
echo "</tbody>\n</table>\n";