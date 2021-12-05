<?php

require_once './controllers/VentaController.php';
require_once './models/Monedas.php';

use Fpdf\Fpdf;

class ManejoArchivos
{
    public function DescargaPDF($request, $response, $args)
    {
        $dato = Venta::obtenerTodos();
        if ($dato)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 15, 'ProgIII Segundo Parcial', 1, 3, 'B');
            $pdf->Ln(3);
            
            $header = array('ID', 'FECHA', 'CANTIDAD', 'nombre', 'IMAGEN', 'NOMBRE');
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(20, 30, 30, 30, 40, 30);
            for ($i = 0; $i < count($header); $i++) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $fill = false;

            foreach ($dato as $v)
            {
                $pdf->Cell($w[0], 6, $v->id, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 6, $v->fecha, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 6, $v->cantidad, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $v->nombre, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $v->imagen, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 6, $v->nombre, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivos/' . $v->nombre .'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivos/' . $v->nombre .'.pdf'));
        }
        else
        {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function DescargaPDFCaro($request, $response, $args)
    {
        $dato = Monedas::obtenerMonedaCara();
        var_dump($dato);
        if ($dato)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 15, 'ProgIII Segundo Parcial', 1, 3, 'B');
            $pdf->Ln(3);
            
            $header = array('ID', 'PRECIO', 'NOMBRE', 'IMAGEN', 'NACIONALIDAD');
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(20, 30, 30, 30, 40, 30);
            for ($i = 0; $i < count($header); $i++) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $fill = false;

            $pdf->Cell($w[0], 6, $dato->id, 'LR', 0, 'C', $fill);
            $pdf->Cell($w[1], 6, $dato->precio, 'LR', 0, 'C', $fill);
            $pdf->Cell($w[3], 6, $dato->nombre, 'LR', 0, 'C', $fill);
            $pdf->Cell($w[5], 6, $dato->foto, 'LR', 0, 'C', $fill);
            $pdf->Cell($w[4], 6, $dato->nacionalidad, 'LR', 0, 'C', $fill);
            $pdf->Ln();
            $fill = !$fill;

            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivos/' . $dato->nombre .'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivos/' . $dato->nombre .'.pdf'));
        }
        else
        {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function DescargaMasVendida($request, $response, $args)
    {
        $dato = Venta::obtenerMasVendida();
        if ($dato)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 15, 'ProgIII Segundo Parcial', 1, 3, 'B');
            $pdf->Ln(3);
            
            $header = array('ID', 'FECHA', 'CANTIDAD', 'nombre', 'IMAGEN', 'NOMBRE');
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(20, 30, 30, 30, 40, 30);
            for ($i = 0; $i < count($header); $i++) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $fill = false;

            foreach ($dato as $v)
            {
                $pdf->Cell($w[0], 6, $v->id, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 6, $v->fecha, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 6, $v->cantidad, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $v->nombre, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $v->imagen, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 6, $v->nombre, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivos/' . $v->nombre .'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivos/' . $v->nombre .'.pdf'));
        }
        else
        {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request,$response,$next)
    {
        $lista = Usuario::obtenerTodos();
        $usuarios = json_encode(array("listaCompleta" => $lista));
        $archivo = fopen("./archivos/usuarios.csv","a");
        $bool = fwrite($archivo, $this->DatosToCSV($usuarios));
        $payload = json_encode(array("mensaje" => "Se guardo el CSV"));
        fclose($archivo);
        if($bool == false)
        {
            $payload = json_encode(array("mensaje" => "No se guardo el archivo"));
        }

        $response->getBody()->write($payload);
        return $bool;
    }

    public function DatosToCSV($datos)
    {
        $lista = json_decode($datos);
        $cadena = "";
        foreach($lista->listaCompleta as $dato)
        {
            $cadena .= "{" . $dato->id . "," . $dato->mail . "," . $dato->tipo . "," . $dato->clave . "}" . ",\n";
        }
        return $cadena;  
    }

    public function GuardarMonedaCSV($request,$response,$next)
    {
        $lista = Monedas::obtenerTodos();
        $monedas = json_encode(array("listaCompleta" => $lista));
        $archivo = fopen("./archivos/monedas.csv","a");
        $bool = fwrite($archivo, $this->MonedaToCSV($monedas));
        $payload = json_encode(array("mensaje" => "Se guardo el CSV"));
        fclose($archivo);
        if($bool == false)
        {
            $payload = json_encode(array("mensaje" => "No se guardo el archivo"));
        }

        $response->getBody()->write($payload);
        return $bool;
    }

    public function MonedaToCSV($datos)
    {
        $lista = json_decode($datos);
        $cadena = "";
        foreach($lista->listaCompleta as $dato)
        {
            $cadena .= "{" . $dato->id . "," . $dato->precio . "," . $dato->nombre . "," . $dato->nacionalidad . "}" . ",\n";
        }
        return $cadena;  
    }
}
