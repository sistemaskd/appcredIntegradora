<?php

class Kondinero_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
	/**
	 * Helper para mostrar la parte de logeado como...
	 */
    public function loggedInAs()
    {
    	$loggedInAs = NULL;
    	
        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) {
        	$identity = $auth->getIdentity();

            $nombre_usuario = implode(' ', array( $identity->nombre
            									, $identity->apellido_paterno
            									, $identity->apellido_materno
            									));
			$loggedInAs = $this->view->partial('system.welcome.phtml',array('usuario'=>$nombre_usuario));
        } else {
        	# -
        }
        
        return $loggedInAs;
    }
}

