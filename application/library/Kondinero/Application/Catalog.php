<?php
	/**
	 * CORE del manejor de catalogos del sistema
	 * @author emoreno
	 */
class Kondinero_Application_Catalog extends Kondinero_Application_Catalog_Abstract implements Kondinero_Application_Interface_Validator
{	
	protected $_isValid;
	protected $_error;

	/**
	 * Devuelve los datos del catalogo
	 */
	public function getData($field)
	{
		$data = NULL;
		$dataType = $this->getDataType($field);
		switch($dataType){
			# Para campos enum
			case 'enum':
				$data = $this->getFromEnumField($field);
				break;
			# Para catalogos completos
			default:
				$data = $this->getFromCatalog($field);
				break;
		}
		
		return $data;
	}
	
	/**
	 * Devuelve el error presentado
	 */
	public function getError()
	{
		return $this->_error;
	}
	
	/**
	 * Devuelve si todo fue valido
	 */
	public function isValid()
	{
		return $this->_isValid;
	}
	

	
}