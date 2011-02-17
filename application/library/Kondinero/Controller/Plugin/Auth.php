<?php
	/**
	 * Plugin para el chequeo de usuarios en sesion que redirecciona automaticamente a los usuarios
	 * @author	emoreno
	 * @since	2011-01-13
	 */
class Kondinero_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    private $_acl = null;
   
    /**
     * Constructor de la clase
     */
    public function __construct(Zend_Acl $acl){
        $this->_acl = $acl;
	}
   
	/**
	 * Preverificación de redirecciones en base al usuario autenticado y su rol
	 */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$auth = Zend_Auth::getInstance();
    	$rol  = 'guest';
    	
		if ($auth->hasIdentity()){
			$storage = $auth->getStorage()->read();
			$rol = $storage->rol;
		} else {
			# --
		}

		$controller = $request->controller;
		$action     = $request->action;
		$resource   = $controller;
		
		$isAllowed = $this->_acl->isAllowed($rol, $resource, $action);
		
		$dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();
		$class  = $dispatcher->loadClass($dispatcher->getControllerClass($request));
        $method = $dispatcher->formatActionName($request->getActionName());
		
		if(    !$isAllowed 
			|| !$dispatcher->isDispatchable($request)
			|| !is_callable(array($class, $method))){
			$request->setControllerName('login')
					->setActionName('login');
		} else {
			# -
		}
    }
    
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$auth = Zend_Auth::getInstance();
    	
    	if ($auth->hasIdentity()){
    		# -	
    	} else {
    		$request->setControllerName('login')
					->setActionName('login');
    	}
    }
   
}