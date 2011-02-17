<?php
	/**
	 * Clase que controla el paso del guardado de referencias personales
	 */
class Kondinero_Application_Solicitud_Wizard_Step_ReferenciasPersonales extends Kondinero_Application_Solicitud_Wizard_Step 
{
	protected $_tableClass = 'Kondinero_Db_Table_Referencia';
	
	/**
	 * Sobreescribimos metodo para guardar la llave de id
	 */
	public function addId($id)
	{
		$info = $this->getTable()->info();
		$postFix = '_'.(count($this->getIds())+1);
		$realPrimaryName = current($info[Zend_Db_Table::PRIMARY]) . $postFix;
		
		$this->_ids[$realPrimaryName] = $id;
	}
}