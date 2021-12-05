<?php
require_once './db/AccesoDatos.php';

class Monedas
{
    public $id;
    public $precio;
    public $nombre;
    public $foto;
    public $nacionalidad;

    public function crearMoneda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO monedas (precio, nombre, foto, nacionalidad) VALUES (:precio, :nombre, :foto, :nacionalidad)");
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio, nombre, foto, nacionalidad FROM monedas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Monedas');
    }

    public static function obtenerUnoId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio, nombre, foto, nacionalidad FROM monedas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Monedas');
    }

    public static function obtenerUnoNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio, nombre, foto, nacionalidad FROM monedas WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Monedas');
    }

    public static function obtenerUnoFoto($foto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio, nombre, foto, nacionalidad FROM monedas WHERE foto = :foto");
        $consulta->bindValue(':foto', $foto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Monedas');
    }

    public function modificarUno()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE monedas SET precio = :precio, nombre = :nombre, foto = :foto, nacionalidad = :nacionalidad WHERE id = :id");
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->rowCount();
    }

    public static function borrarUno($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM monedas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerMonedaCara()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio, nombre, foto, nacionalidad FROM monedas ORDER BY precio DESC");
        $consulta->execute();

        return $consulta->fetchObject('Monedas');
    }

}