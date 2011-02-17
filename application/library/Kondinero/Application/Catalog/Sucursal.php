<?php
	/**
	 * Catalogo para sucursales
	 * @author emoreno
	 */
class Kondinero_Application_Catalog_Sucursal extends Kondinero_Application_Catalog
{
	protected $_tableClass = 'Kondinero_Db_Table_Sucursal';
	
	/**
	 * Devuelve todos los valores de un catalogo segun el usuario
	 */
	public function preProcessSelect(Zend_Db_Select &$select)
	{
		$filterSelect = $this->getTable()->getDefaultAdapter()->select();
		$filterSelect->reset();
		
		$id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
		
		$column = 'codigo';
    	$filterSelect->from('usuario_to_sucursal',$column)
      		  		 ->where('id_usuario = ?', $id_usuario);
    
		$statement = $filterSelect->query();
		$registros = $statement->fetchAll();
       	
		$idsSucursal = array();
		foreach($registros as $registro){
			$idsSucursal[] = $registro[$column];
		}

		if(count($idsSucursal)){
			# -
		} else {
			$idsSucursal[] = -1;
		}
		
		$idsSucursal = implode(',',$idsSucursal);
		$select->where("{$column} IN({$idsSucursal})");
		
	}
	
	public function getRegistros($start = 0,$limit = 10)
			{					
				$registros = array();
				
				$table = new Kondinero_Db_Table_Sucursal();
				$select = $table->getAdapter()->select();
				
				$info = $table->info();
				
				$columns = array('codigo' 	=> 'codigo'
								,'nombre'	=> 'nombre'
								 );
		
				$select->from(array($info[Zend_Db_Table::NAME]), $columns)
					   ->limit($limit,$start);
		
					$statement = $select->query();
					$registros = $statement->fetchAll();
		
			return $registros;
			}
			
			/**
	 * Obtiene el numero total de los registros de la solicitud
	 */
	public function getTotalCount()
	{
		$table = new Kondinero_Db_Table_Sucursal();
		$info = $table->info();
		$select = $table->getAdapter()->select();
		
		$select->from(array($info[Zend_Db_Table::NAME]), array('total'=>'count(*)'));
		$statement = $select->query();
		$resultObject = $statement->fetchObject();
		return $resultObject->total;
	}
	
	/**
	 * 
	 * Action para obtener la informacion sucursal
	 * @param $usuario
	 */
public function getInformation($codigo)
	  {     
				$registros = array();
				
				$table = new Kondinero_Db_Table_Sucursal();
				$select = $table->getAdapter()->select();
				
				$info = $table->info();
				  $columns = array('codigo' 	=> 'codigo'
								,'nombre'			=> 'nombre'
								  );
		
				$select->from(array($info[Zend_Db_Table::NAME]), $columns)
						->where('codigo = ?',$codigo);
					$statement = $select->query();
					$registros = $statement->fetchAll();
		
			return $registros;
	
		}
	
}