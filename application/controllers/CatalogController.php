<?php
	/**
	 * Controlador para gestionar los catalogos del sistema
	 * @author emoreno
	 */
class CatalogController extends Zend_Controller_Action
{
	/**
	 * Inicializa el controlador
	 */
	public function init()
	{
		$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);	
    	
    	parent::init();
	}
	
	/**
	 * En base al caso de uso regresamos los datos necesarios
	 */
	public function getDataAction()
	{
		$data = array();
		
		$request = $this->getRequest();
		
		$useCase = $request->getParam('useCase');
		$field   = $request->getParam('field');
  		$catalog = Kondinero_Application_Catalog_Factory::getFromUseCase($useCase);
		$data    = $catalog->getData($field);
		die(json_encode($data));
		
	}
	
	public function manageUsersAction()
	{
		 $params = array('success' => FALSE);
	     $data = array(); // para recibir o definir las variables
	     $request = $this->getRequest();// Variable Global del PHP para recibir parametros 
	     $nombre = $request->getParam('nombre');
	     $apellido_materno = $request->getParam('apellido_materno');
	     $apellido_paterno = $request->getParam('apellido_paterno');
	     $login = $request->getParam('login');
	     $password = $request->getParam('password');
	     $password_confirmacion = $request->getParam('password_confirmacion');
	     $rol = $request->getParam('rol');
	     $sucursal= $request->getParam('codigo');
		
		if($password==$password_confirmacion)
		{
		$data['nombre']=$nombre;
		$data['apellido_paterno']=$apellido_paterno;
		$data['apellido_materno']=$apellido_materno;
		$data['login']=$login;
		
		$data['password']=md5($password);
		$data['rol']=$rol;
		$tableusuario = new Kondinero_Db_Table_Usuario();
	    $tableusuario->insert($data);
	    
	    # - obtener el id de la tabla usuario insertado
	    $id_usuario = $tableusuario->getAdapter()->lastInsertId();
	    //insert Para usuario to surcursal
	   	$data_usuario_to_sucursal['id_usuario']=$id_usuario;
	   	$data_usuario_to_sucursal['codigo']=$sucursal;
	    $tableusuario = new Kondinero_Db_Table_UsuarioToSucursal();
	    $tableusuario->insert($data_usuario_to_sucursal);
	    
	    
	    
	    
	    $response['success'] = TRUE;
	   	$response['message']= array('reason' =>	'El usuario fue creado con exito');
	    die(json_encode($response));
	  
		}
		else 
		{
		$error['errors'] = array('reason' => 'El password no es igual al password de confirmacion.');
		die(json_encode($error));
		}
		
	
	  
	}
	/**
	 * Action para consultar Usuarios
	 * Enter description here ...
	 * @author fortigoza
	 */
public function consultarUsuarioAction()
	{
	
		$request = $this->getRequest();
		$start = $request->getParam('start') ? $request->getParam('start') : 0;
		$limit = $request->getParam('limit') ? $request->getParam('limit') : 10;
		
		$usuario_consulta = new Kondinero_Application_Catalog_Usuario();
		$registros = $usuario_consulta->getRegistros($start,$limit);
		
		$data = array('total'=>$usuario_consulta->getTotalCount()
		             ,'data'=>$registros);
		
		die(json_encode($data));
	} 
	
	
	
	
	public function getInformativeAction()
	{
		
		$request = $this->getRequest();
		$idUsuario = $request->getParam('id_usuario');
		$sections = $request->getParam('sections');
		$sections = explode(',',$sections);
	
	
		
		$usuario = new Kondinero_Application_Catalog_Usuario();
		$data = $usuario->getInformation($idUsuario);
		
	    $information = array();
		$this->view->data = $data;
		foreach($sections as $section){
			$this->view->section = $section;
			$information[$section] = $this->view->partial('usuario.get-informative.phtml', $this->view); 
		}
		
		die(json_encode($information));
	}
	/**
	 * 
	 * Clase para consultar las sucursales
	 * @author fortigoza
	 */
	public function consultarSucursalAction()
	{
	
		$request = $this->getRequest();
		$start = $request->getParam('start') ? $request->getParam('start') : 0;
		$limit = $request->getParam('limit') ? $request->getParam('limit') : 10;
		
		$usuario_consulta = new Kondinero_Application_Catalog_Sucursal();
		$registros = $usuario_consulta->getRegistros($start,$limit);
		
		$data = array('total'=>$usuario_consulta->getTotalCount()
		             ,'data'=>$registros);
		
		die(json_encode($data));
	} 
	
public function getInformativeSucursalAction()
	{
		
		$request = $this->getRequest();
		$idSucursal = $request->getParam('codigo');
		$sections = $request->getParam('sections');
		$sections = explode(',',$sections);
	
		
		$usuario = new Kondinero_Application_Catalog_Sucursal();
		$data = $usuario->getInformation($idSucursal);
		
	    $information = array();
		$this->view->data = $data;
		foreach($sections as $section){
			$this->view->section = $section;
			$information[$section] = $this->view->partial('sucursal.get-informative.phtml', $this->view); 
		}
		
		die(json_encode($information));
	}
	
public function updateStatusAction()
	{
		$response = array();
		$request = $this->getRequest();
		
		if($request->isPost()){
			$id_usuario = $request->getParam('id_usuario');
			$rol = $request->getParam('rol');
			
			$usuario= new Kondinero_Application_AdministracionUsuario();
			$rowUsuario = $usuario->getRow($id_usuario);
			
			if($rowUsuario && $rol){
				$rol = (string)$rol;
				$rowUsuario->rol = $rol;
				$rowUsuario->save();
				$response['success'] = TRUE;
				$response['message'] = "El Usuario se Actualizo con exito al perfil '{$rol}'";
			} else {
				$response['message'] = 'El usuario no pudo actualizarse';
			}
		} else {
			$response['message'] = 'No se permiten llamadas por GET';
		}
		
		die(json_encode($response));
	}
	
	
	
}