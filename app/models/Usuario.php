<?php
require_once './db/AccesoDatos.php';

class Usuario
{
    public $id;
    public $mail;
    public $tipo;
    public $clave;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (mail, tipo, clave) VALUES (:mail, :tipo, :clave)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mail, tipo, clave FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUno($mail)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mail, tipo, clave FROM usuarios WHERE mail = :mail");
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public function modificarUno()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET mail = :mail, tipo = :tipo, clave = :clave WHERE id = :id");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }

    public static function borrarUno($mail)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE mail = :mail");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':mail', $mail, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function ValidarUsuario($usr)
    {
        $lista = Usuario::obtenerTodos();

        foreach($lista as $u)
        {
            if($u->mail == $usr->mail && $u->clave == $usr->clave)
            {
                $usr->mail = $u->mail;
                $usr->tipo = $u->tipo;
                $usr->clave = $u->clave;
                echo "Bienvenido! " . $usr->tipo;
                return TRUE;
            }
        }
        return FALSE;
    }

    
}