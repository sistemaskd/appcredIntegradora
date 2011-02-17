<?php
	/**
	 * Clase que se encarga de la gestion general de las solicitudes
	 * @autor emoreno
	 */
class Kondinero_Application_Solicitud
{
	private $_id;
	private $_table;
	
	
	/**
	 * Constructor de la clase
	 */
	public function __construct($id = NULL)
	{
		$this->setId($id);
		$this->_table = new Kondinero_Db_Table_Solicitud();
		$this->init();
	}
	
	/**
	 * Devuelve la información relacionada con la solicitud
	 */
	public function getInformation($idSolicitud = NULL)
	{
		$idSolicitud = $idSolicitud ? $idSolicitud : $this->_id; 
		$rowSolicitud = $this->getRow($idSolicitud);
		
		$table				= $this->getTable();
		$dependentTables	= $table->getDependentTables();
		
		$tableMap = array('referencia' => array('id_referencia_1','id_referencia_2'));
		
		$information = array();
		if($rowSolicitud){
			foreach($dependentTables as $alias => $dependentTable){
				$dependentTableClass = new $dependentTable();
				$dependentPrimary = current($dependentTableClass->info(Zend_Db_Table::PRIMARY));
				
				# Mapping
				$row = NULL;
				if(array_key_exists($alias,$tableMap)){
					$row = array();
					foreach($tableMap as $referenceData){
						foreach($referenceData as $referenceId){
							$queryRow = $dependentTableClass->find($rowSolicitud->$referenceId);
							if($queryRow->valid()){
								$row[] = (object)$queryRow->current()->toArray();
							} else {
								# -
							}
						}
					}
				} else {
					$queryRow = $dependentTableClass->find($rowSolicitud->$dependentPrimary);
					if($queryRow->valid()){
						$row = (object)$queryRow->current()->toArray();
					} else {
						# -
					}
				}
				
				if($row){
					$information[$alias] = $row;
				} else {
					$information[$alias] = array();
				}
			}	
		} else {
			$information['error'] = 'No se envio ningun identificador de solicitud a consultar';
		}
		
		return (object)$information;
	}
	
	/**
	 * Realiza el encoding para los datos
	 */
	private function normalizeData($data)
	{
		
	}
	
	/**
	 * Inicializa aspectos generales de la solicitud
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