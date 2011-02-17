<?php
	/**
	 * Factory para importacion de archivos
	 * @author emoreno
	 */
class Kondinero_Application_Administracion_Importacion_Factory
{
	/**
	 * Devuelve una instancia de importacion dado un import type
	 */
	public static function getImporter($importType)
	{
		$importer = FALSE;
		
		switch($importType){
			case 'info963':
				$importer = new Kondinero_Application_Administracion_Importacion_Info963();
				break;
			case 'bnp':
				$importer = new Kondinero_Application_Administracion_Importacion_Bnp();
				break;
			default:
				trigger_error('El import type "'.(string)$import_type.'" no existe');				
		}
		
		$importer->setImportType($importType);
		$importer->init();
		
		return $importer;
	}
}