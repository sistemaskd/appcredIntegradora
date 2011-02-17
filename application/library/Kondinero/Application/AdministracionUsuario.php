<?php
	/**
	 * Clase que se encarga de hacer la gestion de la actualizacion del usuario
	 * @autor fortigoza
	 */
class Kondinero_Application_AdministracionUsuario
{
	private $_id;
	private $_table;
	
	public function __construct($id = NULL)
	{
		$this->setId($id);
		$this->_table = new Kondinero_Db_Table_Usuario();
		$this->init();
	}
		
private function normalizeData($data)
	{
		
	}
	
	/**
	 * Inicializa aspectos generales del usuario
	 */
	public function init()
	{
		return TRUE;
	}
	
	/**
	 * Realiza la inserción de datos
	 */
	public function insertData($data)
	{
		return $this->getTable()->insert($data);
	}
	
	/**
	 * Devuelve un row
	 */
	public function getRow($id = NULL)
	{
		$row = FALSE;
		if($id){
			$row = $this->getTable()->find($id)->current();
		} else {
			# -
		}
		return $row;
	}
	
	/**
	 * Accesor para id
	 */
	public function setId($id)
	{
		$this->_id = (int)$id;
	}
	
	/**
	 * Accesor para id
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Accesor para _table
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
}