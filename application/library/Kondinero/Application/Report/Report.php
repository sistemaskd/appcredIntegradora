<?php
class Kondinero_Application_Report_Report 
{

	
public function getReport()
	{
		
			
    	
				
		$solicitud = new Kondinero_Db_Table_Solicitud(); // instanciar la tabla de solicitud este metodo ya esta creado
	    $info = $solicitud->info(); // Sacando la informacion de la tabla solcitud y se la asigno a INFO
		$select = $solicitud->getAdapter()->select();// me devuelve el select from de table solicitud
		$columns = array('Numero_Solicitud'=> new Zend_Db_Expr("count(id_solicitud)")
						,'Fecha_Creacion'  => "fecha_creacion");
		$select->from($info[Zend_Db_Table::NAME],$columns)
				->group('DATE(fecha_creacion)')
				->order('fecha_creacion');
				$statement= $select->query(); // me devuelve un objeto de consulta si la consulta se ejecuto con exito
				$registros = $statement->fetchAll(); 
			   return $registros;
			
	}
	
	/**
	 * Function para el reporte de vendedores por sucursal
	 * @author fnortigoza
	 * Enter description here ...
	 */
	public function getReportVenta()
	{
		 $solicitud = new Kondinero_Db_Table_Solicitud();
	
	}
	
	
public function reportVendCliente($fecha_incio,$fecha_final,$codigo)
	{
	    
    
    
    	$secrep='ReporteVentaporCliente';
    	$sections=$secrep;
    	$sections = explode(',',$sections);
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
 		    return $registros;
	     
	 
		
		
	

		
	
	
	
}
	

}