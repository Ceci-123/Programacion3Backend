<?php

class Usuario
{
    public string $nombre;
    public string $clave;
    public string $mail;
    public int $id;
    public DateTime $fechaRegistro;
    public static int $primerId;
    public static $primeraVez = false;

    public function getNombre()
    {
        return $this->nombre;
    }

    private static function inicializar()
    {
        self::$primeraVez = true;
        self::$primerId = random_int(1, 10000);
    }
    public function __construct($nombre, $clave, $mail)
    {
        if (!self::$primeraVez) {
            self::inicializar();
        }
        $this->nombre = $nombre;
        $this->clave = $clave;
        $this->mail = $mail;
        $this->id =  self::$primerId;
        $this->fechaRegistro = new DateTime(date('d-m-y h:i:s'));
        self::$primerId++;
    }

    public static function MostrarInformacion(Usuario $usuario)
    {
        echo "Usuario: $usuario->nombre | Clave: $usuario->clave | Mail: $usuario->mail \n ";
        echo "ID: $usuario->id | Fecha de registro: ";
        echo $usuario->fechaRegistro->format('d-m-y h:i:s');
    }

    //esta funcion sirve para el csv
    public function InformacionUsuario(Usuario $usuario)
    {
        $texto = "$usuario->nombre,$usuario->clave,$usuario->mail,$usuario->id,";
        $texto .= $usuario->fechaRegistro->format('d-m-y h:i:s');
        return $texto;
    }

    public static function GuardarUsuarioCSV(Usuario $usuario)
    {
        $archivo = fopen("usuarios.csv", "a");
        $confirmacion = false;
        if (fwrite($archivo, $usuario->InformacionUsuario($usuario) . PHP_EOL) != false) {
            $confirmacion = true;
        }
        fclose($archivo);
        return $confirmacion;
    }

    public static function GuardarListaJSON($usuariosArray)
    {
        $archivo = fopen("usuarios.json", "w");
        $confirmacion = false;

        if (fwrite($archivo, json_encode($usuariosArray, JSON_PRETTY_PRINT) . PHP_EOL) != false) {
            $confirmacion = true;
        }
        fclose($archivo);
        return $confirmacion;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    public static function LeeUsuariosListaJSON($nombreArchivo)
    {
        $archivo = fopen($nombreArchivo, "r");
        $arrayAtributos = array();
        $arrayDeUsuarios = array();

        $json = fread($archivo, filesize($nombreArchivo));
        $arrayAtributos = json_decode($json, true);

        if (!empty($arrayAtributos)) {
            foreach ($arrayAtributos as $usuarioJson) {
                $usuarioAuxiliar = new Usuario($usuarioJson["nombre"], $usuarioJson["clave"], $usuarioJson["mail"]);
                $usuarioAuxiliar->setId($usuarioJson["id"]);
                $usuarioAuxiliar->setFechaRegistro(new DateTime($usuarioJson["fechaRegistro"]["date"]));
                array_push($arrayDeUsuarios, $usuarioAuxiliar);
            }
        }

        fclose($archivo);
        return $arrayDeUsuarios;
    }

    public static function BuscarUsuario($listaDeUsuarios, $nombreUsuario)
    {
        foreach ($listaDeUsuarios as $usuario) {
            if (strcmp($usuario->getNombre(), $nombreUsuario) == 0) {
                return $usuario;
            }
        }
        return null;
    }
}
