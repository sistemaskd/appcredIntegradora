<?php

class Kondinero_View_Helper_LibraryPaths extends Zend_View_Helper_Abstract 
{
	/**
	 * Helper para mostrar la parte de logeado como...
	 */
    public function libraryPaths()
    {
     	$paths  = new stdClass();
     	$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini',APPLICATION_ENV);
     	
     	foreach(@$config->resources->view->helper->libraryPaths->toArray() as $library => $path){
     		$paths->$library = $this->view->baseUrl().$path;
     	}

        return $paths;
    }
}

