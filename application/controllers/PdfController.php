<?php

require_once 'Zend/Loader/Autoloader.php';
require_once('fpdf/fpdf.php');
// register auto-loader
$loader = Zend_Loader_Autoloader::getInstance();

class PdfController extends Zend_Controller_Action 
{


public function reportVendClienteAction()
	{ 
		
		
		$data = array();
		
	
		
    	$params = array('success' => FALSE);
	    $data = array(); // para recibir o definir las variables
    	$request= $this->getRequest();
    	$fecha_inicio = $request->getParam('fecha_incio');
    	$fecha_final = $request->getParam('fecha_final');
    	$sections = $request->getParam('sections');
    	$secrep='ReporteVentaporCliente';
    	$sections=$secrep;
    	$sections = explode(',',$sections);
    	$codigo = $request->getParam('codigo'); 
		$report= new Kondinero_Application_Report_Report();
		$stmt= $report->reportVendCliente($fecha_inicio,$fecha_final,$codigo);
		
		$section='ReporteVentaporCliente';
		$data = array('data'=>$stmt,'section' =>$section);
		$contenido = $this->view->partial('ReporteVentaporCliente.get-informative.phtml',$data);

		$file = 'archivo.xls';
		file_put_contents('archivo.xls',$contenido);
	

//if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=archivo.xls');
    header('Content-Transfer-Encoding: chunked');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header ('Content-type: application/ms-excel');
    //header('Content-Length: ' . filesize($file));
    /*die (json_encode($contenido));
    ob_clean();
    flush();*/
    //readfile($file);
    echo $contenido;
    exit;
//}
	}
}


