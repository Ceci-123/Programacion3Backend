<?php
class Producto
{
    public int $codigoBarras;
    public string $nombre;
    public string $tipo;
    public int $stock;
    public float $precio;

    public function __construct($codigoBarras, $nombre, $tipo, $stock, $precio)
    {
        $this->codigoBarras = $codigoBarras;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->stock = $stock;
        $this->precio = $precio;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getCodigo()
    {
        return $this->codigoBarras;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($cantidad)
    {
        $this->stock = $cantidad;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function Mostrar(Producto $producto)
    {
        echo "Producto: $producto->nombre | Codigo: $producto->codigoBarras | Tipo: $producto->tipo \n ";
        echo "Stock: $producto->stock | Precio $ $producto->precio ";
    }
    public static function GuardarListaProductosJSON($arrayProductos)
    {
        $archivo = fopen("productos.json", "w");
        $confirmacion = false;

        if (fwrite($archivo, json_encode($arrayProductos, JSON_PRETTY_PRINT) . PHP_EOL) != false) {
            $confirmacion = true;
        }
        fclose($archivo);
        return $confirmacion;
    }

    public static function LeerListaProductosJSON($nombreArchivo)
    {
        $archivo = fopen($nombreArchivo, "r");
        $arrayAtributos = array();
        $arrayDeProductos = array();

        $json = fread($archivo, filesize($nombreArchivo));
        $arrayAtributos = json_decode($json, true);

        if (!empty($arrayAtributos)) {
            foreach ($arrayAtributos as $productoJson) {
                $productoAuxiliar = new Producto(
                    $productoJson["codigoBarras"],
                    $productoJson["nombre"],
                    $productoJson["tipo"],
                    $productoJson["stock"],
                    $productoJson["precio"]
                );
                array_push($arrayDeProductos, $productoAuxiliar);
            }
        }
        fclose($archivo);
        return $arrayDeProductos;
    }

    public static function BuscarProducto($listaDeProductos, $productoIngresado)
    {
        foreach ($listaDeProductos as $producto) {
            if (strcmp($producto->getNombre(), $productoIngresado->getNombre()) == 0) {
                return $producto;
            }
        }

        return null;
    }


    public static function BuscarProductoPorCodigo($listaDeProductos, $codigoProducto)
    {
        foreach ($listaDeProductos as $producto) {
            if ($producto->getCodigo() == $codigoProducto) {
                return $producto;
            }
        }

        return null;
    }
}
