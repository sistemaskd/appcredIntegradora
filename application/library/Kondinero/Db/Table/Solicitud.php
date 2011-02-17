<?php
	/**
	 * Clase para la tabla solicitud
	 * @author Ezequiel Moreno Garcia
	 */
class Kondinero_Db_Table_Solicitud extends Zend_Db_Table_Abstract
{
	protected $_name    = 'solicitud';
	protected $_primary = 'id_solicitud';

	protected $_dependentTables = array('cliente'		=>	'Kondinero_Db_Table_Cliente'
									   ,'credito'		=>	'Kondinero_Db_Table_Credito'
									   ,'empleo'		=>	'Kondinero_Db_Table_Empleo'
									   ,'referencia'	=>	'Kondinero_Db_Table_Referencia'
									   );

}