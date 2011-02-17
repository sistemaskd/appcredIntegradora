<?php
	/**
	 * Fabrica de catalogos
	 * @author emoreno
	 */
class Kondinero_Application_Catalog_Factory
{
	/**
	 * Devuelve un catalogo en base al caso de uso
	 * @param string $useCase
	 */
	public static function getFromUseCase($useCase)
	{
		$instance = FALSE;
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/catalog/config.ini','useCase');
		
		if(isset($config->$useCase->catalogClass)){
			$catalogClass = @$config->$useCase->catalogClass;
			$instance = new $catalogClass();
		} else if(isset($config->$useCase->tableClass)){
			$tableClass = @$config->$useCase->tableClass;
			$instance = new Kondinero_Application_Catalog($tableClass);
		} else {
			trigger_error("El caso de uso '{$useCase}' no ha sido configurado",E_USER_ERROR);
		}
		
		$instance->setUseCase($useCase);
		
		return $instance;
	}
}