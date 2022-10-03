<?php

include_once "Usuario.php";
include_once "Producto.php";
include_once "Venta.php";

$nombreUsuario = $_POST['nombre'];
$claveUsuario = $_POST['clave'];
$mailUsuario = $_POST['mail'];
$idUsuario = $_POST['id'];
$fechaUsuario = $_POST['fecha'];

//usuario creado por post
$usuario = new Usuario($nombreUsuario, $claveUsuario, $mailUsuario, $idUsuario, $fechaUsuario);

//creo un array de usuarios
$compradores = array();
array_push($compradores, $usuario);
array_push($compradores, new Usuario("Milo", "password", "milo@gmail.com", 100, "05/03/2022"));
/*Usuario::GuardarListaJSON($compradores);
var_dump($compradores); */
//$compradores = Usuario::LeeUsuariosListaJSON("usuarios.json");

/* $resultado = Usuario::BuscarUsuario($compradores, "milena");
var_dump($resultado); */


//creo un inventario de productos
$inventario = array();
array_push($inventario, new Producto(1234, "humus", "comida", 200, 100.20));
array_push($inventario, new Producto(1235, "coca", "bebida", 300, 800.50));
array_push($inventario, new Producto(1236, "kitkat", "postre", 100, 400.50));
array_push($inventario, new Producto(1237, "papasfritas", "snack", 250, 80.35));
/*
$resultado = Producto::GuardarListaProductosJSON($inventario);
var_dump($resultado); */

//leo desde archivo
/* $inventario = Producto::LeerListaProductosJSON("productos.json");

$productoParaComparar = new Producto(1236, "kitkat", "postre", 100, 400.50);
$resultado = Producto::BuscarProducto($inventario, $productoParaComparar);

$resultado2 = Producto::BuscarProductoPorCodigo($inventario, 1234);
var_dump($resultado2); */

/* $hagoUnaVenta = new Venta("Milo", "1234", 2);
var_dump($hagoUnaVenta); */


Usuario::SubirImagen();
