<?php
require_once './db/AccesoDatos.php';

class Venta
{
    public $id;
    public $fecha;
    public $nombre;
    public $cantidad;
    public $cliente;
    public $imagen;

    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (fecha, nombre, cantidad, cliente, imagen) VALUES (:fecha, :nombre, :cantidad, :cliente, :imagen)");
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fecha, nombre, cantidad, cliente, imagen FROM ventas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerUno($cliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fecha, nombre, cantidad, cliente, imagen FROM ventas WHERE cliente = :cliente");
        $consulta->bindValue(':cliente', $cliente, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Venta');
    }

    public static function TraerPorNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.mail, usuarios.tipo FROM ventas, usuarios WHERE usuarios.id = ventas.id AND ventas.nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Venta');
    }

    public static function TraerPorFecha($fechaUno, $fechaDos, $nacionalidad){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT ventas.id, ventas.nombre, ventas.cantidad, ventas.cliente, ventas.fecha, monedas.nacionalidad FROM ventas, monedas WHERE ventas.id = monedas.id AND ventas.fecha BETWEEN :fechaUno AND :fechaDos AND monedas.nacionalidad = :nacionalidad");
        $consulta->bindValue(':fechaUno', $fechaUno, PDO::PARAM_STR);
        $consulta->bindValue(':fechaDos', $fechaDos, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function Listar($venta)
    {
        return 'Id:'.$venta->id.' - Fecha: '.$venta->fecha.' - Nombre: '.$venta->nombre.' - Cantidad: '.$venta->cantidad.' - Cliente: '.$venta->cliente;
    }

    public static function obtenerMasVendida()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fecha, nombre, cantidad, cliente, imagen FROM ventas ORDER BY nombre DESC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }
}