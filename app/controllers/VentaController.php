<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Venta.php';
require_once './interfaces/IApiUsable.php';

class VentaController
{
    public function CargarUno(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();

        $vt = new Venta();

        $vt->fecha = Date('Y-m-d');
        $vt->cantidad = $parametros['cantidad'];
        $vt->cliente = $parametros['cliente'];
        $vt->imagen = $_FILES['imagen']['name'];
        $destino = './FotosCripto/' . $vt->fecha . $vt->cliente . $vt->imagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
        $datos = $vt->crearVenta();
        if($datos)
        {
            $payload = json_encode(array("mensaje" => "Guardado correctamente"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoPorNombre($request, $response, $args)
    {
        $nombreMoneda = $args['moneda'];
        $moneda = Venta::TraerPorNombre($nombreMoneda);
        $payload = json_encode(array("lista monedas" => $moneda));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorFechas($request, $response, $args)
    {
        $nacionalidad = $args['nacionalidad'];
        $fechaUno = $args['fechaUno'];
        $fechaDos = $args['fechaDos'];
        $moneda = Venta::TraerPorFecha($fechaUno, $fechaDos, $nacionalidad);
        $payload = json_encode(array("lista monedas" => $moneda));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
