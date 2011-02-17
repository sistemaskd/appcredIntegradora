<?php
	/**
	 * Clase para consulta de usuarios (Catalogo Usuarios)
	 * @author fortigoza
	 */
class Kondinero_Application_Catalog_Usuario
{
		public function getRegistros($start = 0,$limit = 10)
			{					
				$registros = array();
				
				$table = new Kondinero_Db_Table_Usuario();
				$select = $table->getAdapter()->select();
				
				$info = $table->info();
				
				$columns = array('id_usuario' 	=> 'id_usuario'
								,'login'			=> 'login'
								,'nombre'	=> 'nombre'
								,'apellido_paterno'	=> 'apellido_paterno'
								,'apellido_materno'  => 'apellido_materno'
								,'email'	=> 'email'
								,'rol' => 'rol'
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
		$table = new Kondinero_Db_Table_Usuario();
		$info = $table->info();
		$select = $table->getAdapter()->select();
		
		$select->from(array($info[Zend_Db_Table::NAME]), array('total'=>'count(*)'));
		$statement = $select->query();
		$resultObject = $statement->fetchObject();
		return $resultObject->total;
	}
	
	public function getInformation($usuario)
	  {     
				$registros = array();
				
				$table = new Kondinero_Db_Table_Usuario();
				$select = $table->getAdapter()->select();
				
				$info = $table->info();
				  $id_usuario=  Zend_Auth::getInstance()->getIdentity()->id_usuario;
				  $columns = array('id_usuario' 	=> 'id_usuario'
								,'login'			=> 'login'
								,'nombre'	=> 'nombre'
								,'apellido_paterno'	=> 'apellido_paterno'
								,'apellido_materno'  => 'apellido_materno'
								,'email'	=> 'email'
								,'rol' => 'rol'
								);
		
				$select->from(array($info[Zend_Db_Table::NAME]), $columns)
						->where('id_usuario = ?',$usuario);
					$statement = $select->query();
					$registros = $statement->fetchAll();
		
			return $registros;
	
		}
			
			
}