<?php

session_start();
setcookie("nombredeusuario", "galletita");

$_SESSION["CLAVE"] = "misesion";
var_dump($_SESSION);

echo "hola";

var_dump($_COOKIE);
