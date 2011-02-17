<?php
	/**
	 * Controlador encargado de realizar acabo el login y logout del usuario
	 * @author emoreno
	 * @since  2011-01-13
	 */
class LoginController extends Zend_Controller_Action
{	
	/**
	 * Inicializamos el controlador
	 */
	public function init()
	{
		$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('process','json');
		
		$contextSwitch->initContext();	
    	
    	parent::init();
	}
	/**
	 * Acción principal del controlador
	 */
	public function loginAction()
	{
		$this->_helper->layout()->enableLayout();
		$this->view->headScript()->offsetSetFile(2, $this->view->baseUrl().'/application/js/login.login.js' );
	}
	
	/**
	 * Metodo encargado de destruir las identidades del usuario
	 */
	public function logoutAction()
	{
		# redireccionamos a la accion inicial
		Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login');
	}
	
    /**
     * Procesa los resultados que llegan al controlador
     */
    public function processAction()
    {
		$request = $this->getRequest();

		$params = array('success' => FALSE);
		
    	if($request->isPost()){
			
    		$login		= $request->getParam('login');
    		$password	= $request->getParam('password');
    		
			$login    = $login ? addslashes($login) : -1;
			$password = $password ? addslashes($password) : -1;
			
			$adapter = Zend_Db_Table::getDefaultAdapter();
			$auth    = new Zend_Auth_Adapter_DbTable($adapter,'usuario','login','password','MD5(?)');
	 
			# Verificamos contra los datos ingresados
			$auth->setIdentity($login)->setCredential($password);

			$result = $auth->authenticate();

			if($result->isValid()){
				# Autenticación valida
				$params['success'] = TRUE;
				# Guardamos los datos de sesion
				$userInfo = $auth->getResultRowObject(null, 'password');
				$authStorage = Zend_Auth::getInstance()->getStorage();
				$authStorage->write($userInfo);
			} else {
				# Autenticación erronea
				$params['errors'] = array('reason' => 'Los datos de acceso son incorrectos.');
			}

		} else {
			// -
		}
		die(json_encode($params));
    }

}