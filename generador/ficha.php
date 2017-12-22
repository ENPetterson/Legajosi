<title>ficha</title>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('max_execution_time', 300);
error_reporting(-1);

//require('fpdm.php');
require('./fpdf/fpdf.php');

require('./vendor/autoload.php');

use mikehaertl\pdftk\Pdf;

$datos = (array) json_decode($_POST['datos']);

$numeroFicha = str_pad($datos['id'], 6, '0', STR_PAD_LEFT);

$contadorHojas = 0;
$hojas = array();

foreach ($datos['fichas'] as $objFicha){
    $ficha = (array) $objFicha;
    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    $pdf = new PDF('01-ficha.pdf');
    $pdf->fillForm($ficha)
        ->flatten()
        ->saveAs($nombreArchivo);
    $hojas[] = $nombreArchivo;
}

foreach ($datos['convenios'] as $objConvenio){
    $convenio = (array) $objConvenio;
    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    $pdf = new PDF('02-convenio.pdf');
    $pdf->fillForm($convenio)
        ->flatten()
        ->saveAs($nombreArchivo);
    $hojas[] = $nombreArchivo;
}

$perfil = (array) $datos['perfil'];
$contadorHojas++;
$nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
$pdf = new PDF('03-perfil.pdf');
$pdf->fillForm($perfil)
    ->flatten()
    ->saveAs($nombreArchivo);
$hojas[] = $nombreArchivo;

$autorizacionAllaria = (array) $datos['autorizacionAllaria'];
$contadorHojas++;
$nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
$pdf = new PDF('04-autorizacionAllaria.pdf');
$pdf->fillForm($autorizacionAllaria)
    ->flatten()
    ->saveAs($nombreArchivo);
$hojas[] = $nombreArchivo;

if (isset($datos['autorizacionTercero'])){
    $autorizacionTercero = (array) $datos['autorizacionTercero'];
    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    $pdf = new PDF('05-autorizacionTercero.pdf');
    $pdf->fillForm($autorizacionTercero)
        ->flatten()
        ->saveAs($nombreArchivo);
    $hojas[] = $nombreArchivo;
}

if (isset($datos['autorizacionProductor'])){
    $autorizacionProductor = (array) $datos['autorizacionProductor'];
    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    $pdf = new PDF('06-autorizacionProductor.pdf');
    $pdf->fillForm($autorizacionProductor)
        ->flatten()
        ->saveAs($nombreArchivo);
    $hojas[] = $nombreArchivo;
}


foreach ($datos['pep'] as $objPep){
    $pep = (array) $objPep;
    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    $pdf = new PDF('07-pep.pdf');
    $pdf->fillForm($pep)
        ->flatten()
        ->saveAs($nombreArchivo);
    $hojas[] = $nombreArchivo;
}

foreach ($datos['ocde'] as $objOcde){
    $ocde = (array) $objOcde;
    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    $pdf = new PDF('08-ocde.pdf');
    $pdf->fillForm($ocde)
        ->flatten()
        ->saveAs($nombreArchivo);
    $hojas[] = $nombreArchivo;
}

if (isset($datos['instructivo'])){
    $instructivo = (array) $datos['instructivo'];
    if ($instructivo['asociarCuenta'] == 'S'){
        $contadorHojas++;
        $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
        $pdf = new PDF('09-instructivo.pdf');
        $pdf->fillForm($instructivo)
            ->flatten()
            ->saveAs($nombreArchivo);
        $hojas[] = $nombreArchivo;
    }
}




foreach ($datos['imagenes'] as $objImagenes){
    
    $maxWidth = 190;
    $maxHeight = 220;

    $imagen = (array) $objImagenes;
    
    $imagenes = 1;
    if (array_key_exists('imagenDocumento', $imagen)){
        $pic1 = $imagen['imagenDocumento'];
        if (array_key_exists('imagenDorso', $imagen)){
            $imagenes = 2;
            $pic2 = $imagen['imagenDorso'];
        }
    } else {
        $pic1 = $imagen['imagenDorso'];
    }
    
    $width1 = $maxWidth;
    $height1 = $maxHeight;    
    if ($imagenes == 1){
        $pic1 = imagenRecortada($pic1, $width1, $height1);
    } else {
        $width1 = $maxWidth / 2;
        $pic1 = imagenRecortada($pic1, $width1, $height1);
        $width2 = $maxWidth / 2;
        $height2 = $maxHeight;    
        $pic2 = imagenRecortada($pic2, $width2, $height2);
    }

    $contadorHojas++;
    $nombreArchivo = './hoja-' . $contadorHojas . '.pdf';
    
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image($pic1, 10, 15, $width1, $height1, 'png');
    if ($imagenes == 2){
        $pdf->Image($pic2, 110, 15, $width2, $height2, 'png');
    }
    $pdf->SetFont('Arial','',14);
    $pdf->SetY(245);
    $pdf->Cell(0,0,'Es copia fiel del original.');
    $pdf->SetY(255);
    $pdf->Cell(0,0,'Firma:');
    $pdf->SetY(275);
    $pdf->Cell(10,0,iconv('UTF-8', 'windows-1252', 'Aclaración:'));
    $pdf->SetFont('Arial','B',14);
    $pdf->SetX(38);
    $pdf->Cell(10,0,iconv('UTF-8', 'windows-1252', $imagen['nombre']));
    $pdf->Output('F', $nombreArchivo);
    
    $hojas[] = $nombreArchivo;
}





$pdf = new PDF($hojas);
$pdf->send('ficha-' . $numeroFicha . '.pdf');

foreach ($hojas as $archivo){
    unlink($archivo);
}

function imagenRecortada($imagen, &$width, &$height){
    
    $maxWidth = $width;
    $maxHeight = $height;
    
    $info = getimagesize($imagen);
    
    $origWidth =  $info[0];
    $origHeight = $info[1];

    $coeficiente = $maxWidth / $origWidth;
    $width = floor($origWidth * $coeficiente);
    $height = floor($origHeight * $coeficiente);

    if ($height > $maxHeight){
        $coeficiente = $maxHeight / $height;
        $width = floor($width * $coeficiente);
        $height = $maxHeight;
    }


    $pic = imagecreatefromstring(base64_decode(explode(',',$imagen)[1]));

    imagefilter($pic, IMG_FILTER_GRAYSCALE);

    $img = imagecreatetruecolor($width * 15 , $height * 15);


    imagecopyresized($img, $pic, 0, 0, 0, 0, $width * 15, $height * 15, $origWidth, $origHeight);

    ob_start();
    imagepng($img);
    $pic = ob_get_contents();
    ob_end_clean();

    $pic = 'data:image/png;base64,' . base64_encode($pic);
    
    return $pic;

}