<?php

include_once "Producto.php";

class Venta
{
    public int $producto;
    public string $usuario;
    public int $cantidad;

    public function __construct($usuario, $codigoDeBarras, $cantidad)
    {
        $this->usuario = $usuario;
        $this->producto = $codigoDeBarras;
        $this->cantidad = $cantidad;
    }

    public function InformacionVenta(Venta $venta)
    {
        $cadenaDeTexto = "$venta->usuario,";
        $cadenaDeTexto .= $venta->producto;
        $cadenaDeTexto .= ",$venta->cantidad";
        return $cadenaDeTexto;
    }

    public static function GuardarListaVentasJSON($arrayVentas)
    {
        $archivo = fopen("ventas.json", "w");
        $confirmacion = false;

        if (fwrite($archivo, json_encode($arrayVentas, JSON_PRETTY_PRINT) . PHP_EOL) != false) {
            $confirmacion = true;
        }
        fclose($archivo);
        return $confirmacion;
    }

    public static function LeerVentasListaJSON($nombreArchivo)
    {
        $archivo = fopen($nombreArchivo, "r");
        $arrayAtributos = array();
        $arrayDeVentas = array();

        $json = fread($archivo, filesize($nombreArchivo));
        $arrayAtributos = json_decode($json, true);

        if (!empty($arrayAtributos)) {
            foreach ($arrayAtributos as $ventasJson) {
                $ventaAux = new Venta($ventasJson["usuario"], $ventasJson["producto"], $ventasJson["cantidad"]);
                array_push($arrayDeVentas, $ventaAux);
            }
        }
        fclose($archivo);
        return $arrayDeVentas;
    }

    public function BuscarProducto($listaDeProductos, $productoIngresado)
    {
        foreach ($listaDeProductos as $producto) {
            if ($producto->getNombre() == $productoIngresado->getNombre()) {
                return $producto;
            }
        }

        return null;
    }

    public function BuscarProductoPorId($listaDeProductos, $id)
    {
        foreach ($listaDeProductos as $producto) {
            if ($producto->getCodigo() == $id) {
                return $producto;
            }
        }

        return null;
    }

    public function ConfirmarVenta($listaDeProductos, $listaDeUsuarios, $producto, $usuario)
    {
        $productoEnLista = Producto::BuscarProductoPorCodigo($listaDeProductos, $producto);

        if (
            $productoEnLista != null && $productoEnLista->getStock() - $this->cantidad > 0
            && Usuario::BuscarUsuario($listaDeUsuarios, $usuario) != null
        ) {
            $productoEnLista->setStock($productoEnLista->getStock() - $this->cantidad);
            echo "Venta realizada con exito\n";
        } else {
            echo "No se pudo confirmar la venta\n";
        }
    }
}
