<?php

require_once './models/Monedas.php';
require_once './interfaces/IApiUsable.php';

class MonedasController
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $monedas = new Monedas();
        $monedas->precio = $parametros['precio'];
        $monedas->nombre = $parametros['nombre'];
        $archivo = $request->getUploadedFiles();
        if(array_key_exists("foto", $archivo))
        {
            $monedas->foto = $_FILES['foto']['tmp_name'];
        }
        $destino = './archivos/' . $monedas->nombre;
        move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
        $monedas->nacionalidad = $parametros['nacionalidad'];
        $datos = $monedas->crearMoneda();
        if($datos)
        {
            $payload = json_encode(array("mensaje" => "Criptomoneda agregada "));
        }
        else
        {
            $payload = json_encode(array("error" => "la criptomoneda ya existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Monedas::obtenerTodos();
        if(count($lista) > 0)
        {
            $payload = json_encode(array("lista" => $lista));
        }
        else
        {
            $payload = json_encode(array("error" => "No hay datos agregados"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorNacionalidad($request, $response, $args)
    {
        $nacionalidad = $args['nacionalidad'];

        $lista = Monedas::obtenerUnoNacionalidad($nacionalidad);
        if($lista)
        {
            $payload = json_encode(array("lista" => $lista));
        }
        else
        {
            $payload = json_encode(array("error" => "No hay datos agregados"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoPorID($request, $response, $args)
    {
        $id = $args['id'];
        $moneda = Monedas::obtenerUnoId($id);

        $payload = json_encode($moneda);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $precio = $parametros['precio'];
        $nombre = $parametros['nombre'];
        $nacionalidad = $parametros['nacionalidad'];
        $foto = 'sin foto';

        $archivos = $request->getUploadedFiles();
        if($archivos['foto']->getError() === UPLOAD_ERR_OK)
        {
          $destino="./fotos/";
          $nombreAnterior=$archivos['foto']->getClientFilename();
          $extension= explode(".", $nombreAnterior);
          $extension=array_reverse($extension);
          $destino=$destino . $nombre.".".$extension[0];
          $foto = $destino;
          
          $auxCripto = Monedas::obtenerUnoFoto($foto);

          if($auxCripto != false && $auxCripto->foto == $foto)
          {
            $destino="./backup/";
            $nombreAnterior=$archivos['foto']->getClientFilename();
            $extension= explode(".", $nombreAnterior);
            $extension=array_reverse($extension);
            $destino=$destino . $nombre.".".$extension[0];
            $foto = $destino;
          }
          $archivos['foto']->moveTo($destino);

        }
        $criptomoneda = new Monedas();
        $criptomoneda->id = $id;
        $criptomoneda->precio = $precio;
        $criptomoneda->nombre = $nombre;
        $criptomoneda->foto = $foto;
        $criptomoneda->nacionalidad = $nacionalidad;
        $columnas = $criptomoneda->modificarUno();

        if($columnas != false)
        {
          $payload = json_encode(array("mensaje" => "Criptomoneda modificada con exito"));
        }
        else
        {
          $payload = json_encode(array("mensaje" => "Error al modificar"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        $datos = Monedas::borrarUno($id);
        if($datos)
        {
            $payload = json_encode(array("mensaje" => "Moneda eliminada"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Moneda no encontrada"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

