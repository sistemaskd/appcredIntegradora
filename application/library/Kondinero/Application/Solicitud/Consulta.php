<?php
class Kondinero_Application_Solicitud_Consulta extends Kondinero_Application_Solicitud
{
	/**
	 * Obtiene los campos del listado para la consulta
	 */
	public function getRegistros($start = 0,$limit = 10)
	{
		$registros = array();
		
		$table = $this->getTable();
		$select = $table->getAdapter()->select();
		
		$info = $table->info();
		
		$columns = array('id_solicitud' 	=> 'so.id_solicitud'
						,'nombre'			=> 'cl.nombre'
						,'apellido_paterno'	=> 'cl.apellido_paterno'
						,'apellido_materno'	=> 'cl.apellido_materno'
						,'codigo_sucursal'  => 'cr.codigo_sucursal'
						,'monto_credito'	=> 'cr.monto_credito'
						);

		$select->from(array('so'=>$info[Zend_Db_Table::NAME]), $columns)
			   ->limit($limit,$start);

		$this->decorate($select);

		$statement = $select->query();
		$registros = $statement->fetchAll();

		return $registros;
	}
	
	/**
	 * Obtiene el numero total de los registros de la solicitud
	 */
	public function getTotalCount()
	{
		$table = $this->getTable();
		$info = $table->info();
		$select = $table->getAdapter()->select();
		
		$select->from(array('so'=>$info[Zend_Db_Table::NAME]), array('total'=>'count(*)'));
		
		$this->decorate($select);
		
		$statement = $select->query();
		$resultObject = $statement->fetchObject();
		return $resultObject->total;
	}
	
	/**
	 * Decora el select
	 */
	public function decorate(Zend_Db_Select &$select)
	{
		$id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
		
		$select->joinInner(array('cl'=>'cliente'),'cl.id_cliente = so.id_cliente',array())
			   ->joinInner(array('cr'=>'credito'),'cr.id_credito = so.id_credito',array())
			   ->joinInner(array('su'=>'sucursal'),'su.codigo = cr.codigo_sucursal',array())
			   ->joinInner(array('uts'=>'usuario_to_sucursal'),'uts.codigo = su.codigo',array())
			   ->where('uts.id_usuario = ?',$id_usuario);	
			   
	}

}