<?php

require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController 
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usr = new Usuario();
    $usr->mail = $parametros['mail'];
    $usr->tipo = $parametros['tipo'];
    $usr->clave = $parametros['clave'];
    
    $datos = $usr->crearUsuario();
    if($datos)
    {
      $payload = json_encode(array("mensaje" => "OK " . $usr->tipo));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $usr = $args['mail'];
    $usuario = Usuario::obtenerUno($usr);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();

      $usr = new Usuario();
      $usr->id = $args['id'];
      $usr->mail = $parametros['mail'];
      $usr->tipo = $parametros['tipo'];
      $usr->clave = $parametros['clave'];

      $columnas = $usr->modificarUno();

      if($columnas != false)
      {
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
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

      $usuario = Usuario::obtenerUno($id);

      if($usuario != false)
      {
        Usuario::borrarUno($id);
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro usuario"));
      }
      
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

  public function LogIn($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usr = new Usuario();
    $usr->mail = $parametros['mail'];
    $usr->tipo = $parametros['tipo'];
    $usr->clave = $parametros['clave'];

    var_dump($usr);

    if(Usuario::ValidarUsuario($usr))
    {
      $datos = array('mail' => $usr->mail, 'tipo' => $usr->tipo, 'clave' => $usr->clave);
      $token = AutentificadorJWT::CrearToken($datos);
      $payload = json_encode(array("jwt" => $token));
    }
    else
    {
      $payload = json_encode(array('error' => 'No existe el usuario'));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  
}
