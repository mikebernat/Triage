<?php

$root        = realpath(dirname(dirname(__FILE__)));

$path = array(
    $root,
    get_include_path()
    );
set_include_path(implode(PATH_SEPARATOR, $path));

error_reporting(E_ALL | E_STRICT);