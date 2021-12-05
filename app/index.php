<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

/* require_once './db/AccesoDatos.php'; */
require_once './middlewares/AuthJWT.php';
require_once './middlewares/MWAccesos.php';

require_once './controllers/ManejoArchivos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/MonedasController.php';
require_once './controllers/VentaController.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$app->addErrorMiddleware(true, true, true);

$app->group('/parcial', function (RouteCollectorProxy $group) {

  //1
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->post('/LogIn', \UsuarioController::class . ':LogIn');

  //2
  $group->post('/AltaMonedas', \MonedasController::class . ':CargarUno')
  ->add(\MWAccesos::class . ':EsAdministrador');

  //3
  $group->get('/MostrarTodos', \MonedasController::class . ':TraerTodos');

  //4
  $group->get('/TraerPorNacionalidad/{nacionalidad}', \MonedasController::class . ':TraerPorNacionalidad');

  //5
  $group->get('/TraerPorID/{id}', \MonedasController::class . ':TraerUnoPorID')
  ->add(\MWAccesos::class . ':EsClienteYAdmin');

  //6
  $group->post('/AltaVenta', \VentaController::class . ':CargarUno')
  ->add(\MWAccesos::class . ':EsClienteYAdmin');

  //7
  $group->get('/TraerPorFechas', \VentaController::class . ':TraerPorFechas')->add(\MWAccesos::class . ':EsAdministrador');
  
  //8
  $group->get('/TraerPorNombre/{nombre}', \VentaController::class . ':TraerUnoPorNombre')->add(\MWAccesos::class . ':EsAdministrador');

  //9
  $group->delete('/BorrarPorId', \MonedasController::class . ':BorrarUno')
  ->add(\MWAccesos::class . ':EsAdministrador');

  //10
  $group->put('/ModificarMoneda', \MonedasController::class . ':ModificarUno')
  ->add(\MWAccesos::class . ':EsAdministrador');

  //11
  $group->get('/GenerarPDF', \ManejoArchivos::class . ':DescargaPDF');

  //12
  $group->get('/GenerarPDFCaro', \ManejoArchivos::class . ':DescargaPDFCaro')
  ->add(\MWAccesos::class . ':EsAdministrador');

  //13
  $group->get('/GenerarPDFVendida', \ManejoArchivos::class . ':DescargaMasVendida')
  ->add(\MWAccesos::class . ':EsAdministrador');

  //14
  $group->get('/GuardarCSV', \ManejoArchivos::class . ':GuardarCSV')
  ->add(\MWAccesos::class . ':EsAdministrador');

  $group->get('/GuardarMonedaCSV', \ManejoArchivos::class . ':GuardarMonedaCSV')
  ->add(\MWAccesos::class . ':EsAdministrador');
  
  

});

$app->get('[/]', function (Request $request, Response $response) {
  $response->getBody()->write("Slim Framework 4 PHP");
  return $response;
});

$app->run();
