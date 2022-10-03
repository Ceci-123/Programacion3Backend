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

    public static function LeeUsuariosCSV($nombreArchivo)
    {
        $archivo = fopen($nombreArchivo, "r");
        $arrayAtributos = array();
        $arrayDeUsuarios = array();

        while (!feof($archivo)) {
            $arrayAtributos = fgetcsv($archivo);
            if (!empty($arrayAtributos)) {
                $usuarioAuxiliar = new Usuario($arrayAtributos[0], $arrayAtributos[1], $arrayAtributos[2]);
                array_push($arrayDeUsuarios, $usuarioAuxiliar);
            }
        }
        return $arrayDeUsuarios;
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

    //funcion para las imagenes

    public static function SubirImagen()
    {
        $nombre = $_FILES["imagen"]["name"];

        //INDICO CUALES SERAN LOS DESTINOS DE LOS ARCHIVOS SUBIDOS Y SUS TIPOS
        $destinos = array();
        $tiposArchivo = array();
        $destino =  $nombre;
        array_push($destinos, $destino);
        array_push($tiposArchivo, pathinfo($destino, PATHINFO_EXTENSION));

        $uploadOk = TRUE;
        $mensaje = '';

        //VERIFICO QUE LOS ARCHIVOS NO EXISTAN
        foreach ($destinos as $destino) {
            if (file_exists($destino)) {
                $mensaje = "El archivo {$destino} ya existe. Verifique!!!";
                $uploadOk = FALSE;
                break;
            }
        }

        //OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
        //IMAGEN, RETORNA FALSE
        $tmpName = $_FILES["imagen"]["tmp_name"];
        $i = 0;

        $esImagen = getimagesize($tmpName);

        if ($esImagen) { //NO ES UNA IMAGEN
            //SOLO PERMITO CIERTAS EXTENSIONES
            if (
                $tiposArchivo[$i] != "jpg" && $tiposArchivo[$i] != "jpeg" && $tiposArchivo[$i] != "gif"
                && $tiposArchivo[$i] != "png" && $tiposArchivo[$i] != "JPG"
            ) {
                $mensaje =  "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
                $uploadOk = FALSE;
            }
        }

        $i++;

        //VERIFICO SI HUBO ALGUN ERROR, CHEQUEANDO $uploadOk
        if ($uploadOk === FALSE) {

            $mensaje =  "<br/>NO SE PUDIERON SUBIR LOS ARCHIVOS.";
        } else {
            //MUEVO LOS ARCHIVOS DEL TEMPORAL AL DESTINO FINAL
            if (move_uploaded_file($tmpName, $destinos[0])) {
                $mensaje =  "<br/>El archivo " . basename($tmpName) . " ha sido subido exitosamente.";
            } else {
                $mensaje =  "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo " . basename($tmpName) . ".";
            }
        }

        echo $mensaje;
    }
}
