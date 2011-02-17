<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{   
	/**
	 * Inicializa los plugins
	 */
	protected function _initPlugins()
	{
		$frontController = Zend_Controller_Front::getInstance();
 
		# Autenticacion
        $frontController->registerPlugin(new Kondinero_Controller_Plugin_Auth(new Kondinero_Acl()));
    }
}

