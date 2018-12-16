<?php

function phpWrapperFromFile($filename)
{
    ob_start();
    if (file_exists($filename) && !is_dir($filename))
    {
        include($filename);
    }
    // nacte to z outputu
    $obsah = ob_get_clean();
    return $obsah;
}