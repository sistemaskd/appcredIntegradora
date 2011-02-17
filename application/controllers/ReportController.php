<?php

	/*
	 * @author Nelson Ortigoza
	 * Controlador para reportes
	 */
class ReportController extends Zend_Controller_Action
{


	
	public  function getDatoAction()
	{
		$data = array();
		$request = $this->getRequest();
		$report= new Kondinero_Application_Report_Report();
		$data = $report->getReport();
		die(json_encode($data));
	
	}

		
	
	public function reportSolicitudAction()
	{
		
			$this->_helper->layout()->disableLayout(); //para que no cargue el contenido html
    	$this->_helper->viewRenderer->setNoRender(true);	// para que no carque contenido html
    	
    	$request= $this->getRequest();
    	$solicitud = new Kondinero_Db_Table_Solicitud();
    	$info = $solicitud->info();
    	$select = $solicitud->getDefaultAdapter()->select();
    	$columns = array('numero_solicitud'=> new Zend_Db_Expr("count(id_solicitud)")
    					,'fecha_creacion' => "fecha_creacion");
    	$select->from($info[Zend_Db_Table_Abstract::NAME],$columns)
    			->group('DATE(fecha_creacion)')
    			->order('fecha_creacion');
    			$statement= $select->query();
    			$registros= $statement->fetchAll();
    			die(json_encode($registros));
		
		
	}
	
	/**
	 * Function para consultar las solicitudes por sucursal
	 * @author fnortigoza
	 * Enter description here ...
	 */
public function reportVendAction()
	{
	
	
    	
    	$request= $this->getRequest();
    	$columns = array('numero_solicitud'=> new Zend_Db_Expr("count(codigo_vendedor)")
    					,'codigo' => "s.codigo"
    					,'codigo_vendedor' => "v.codigo_vendedor");
    		   $credito = new Kondinero_Db_Table_Credito();
    			$info = $credito->info();
    			$select = $credito->getDefaultAdapter()->select();
	
              $select->from(array('c'=>'credito'),$columns)
             ->join(array('s'=>'sucursal'),'c.codigo_sucursal = s.codigo',array())
             ->join(array('v'=>'vendedor'),'s.codigo=v.sucursal',array())
             //->where('c.codigo_sucursal = ?',$codigo_sucursal);
             ->group(array('v'=>'codigo_vendedor'));
            $statement = $select->query();
			$registros = $statement->fetchAll();

		die(json_encode($registros));
             
              	
    	/*$solicitud = new Kondinero_Db_Table_Solicitud();
    	$info = $solicitud->info();
    	$select = $solicitud->getDefaultAdapter()->select();
    	$columns = array('numero_solicitud'=> new Zend_Db_Expr("count(id_solicitud)")
    					,'fecha_creacion' => "fecha_creacion");
    	$select->from($info[Zend_Db_Table_Abstract::NAME],$columns)
    			->group('DATE(fecha_creacion)')
    			->order('fecha_creacion');
    			$statement= $select->query();
    			$registros= $statement->fetchAll();
    			die(json_encode($registros));*/
		
		
	}
	
	public function reportVendClienteAction()
	{
		
		
		$data = array();
		$request = $this->getRequest();
		$report= new Kondinero_Application_Report_Report();
		$data = $report->getReport();
		die(json_encode($data));
	    
	
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
    	$columns = array('vendedor'=>"c.vendedor"
    					,'codigo_sucursal' => "cr.codigo_sucursal"
    					,'folio' => "c.folio"
    					,'nombre' => "c.nombre"
    					,'plazo' => "cr.plazo"
    					,'monto_credito' => "cr.monto_credito"
    					,'fecha_creacion' => "s.fecha_creacion");
    		   $credito = new Kondinero_Db_Table_Cliente();
    			$info = $credito->info();
    			$select = $credito->getDefaultAdapter()->select();
	
              $select->from(array('c'=>'cliente'),$columns)
             ->join(array('s'=>'solicitud'),'c.id_cliente = s.id_cliente',array())
             ->join(array('cr'=>'credito'),'s.id_credito=cr.id_credito',array())
             ->where('cr.codigo_sucursal = ?',$codigo);
            $statement = $select->query();
			$registros = $statement->fetchAll();

	     $data=$registros;
	     
	    $information = array();
		$this->view->data = $data;
		foreach($sections as $section){
			$this->view->section = $section;
			$information[$section] = $this->view->partial('ReporteVentaporCliente.get-informative.phtml', $this->view); 
		}
		
	
	    die(json_encode($information));
		//die(json_encode($registros));
	
	
	
}
}