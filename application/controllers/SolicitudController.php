<?php

	/**
	 * Controlador de las solicitudes 
	 * @author emoreno
	 */
class SolicitudController extends Zend_Controller_Action
{	
	/**
	 * Inicializamos el controlador
	 */
	public function init()
	{
		$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('wizard','json');
		$contextSwitch->addActionContext('consultar','json');
		$contextSwitch->addActionContext('getInformative','json');
		
		$contextSwitch->initContext();	
    	
		# -
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->query("SET NAMES 'utf8'");
		Zend_Db_Table::setDefaultAdapter($db);
		# -
		
		
    	parent::init();
	}
	
	/**
	 * Accion principal del controlador
	 */
	public function indexAction()
	{
		$this->_helper->redirector('wizard');
	}
	
	/**
	 * Wizard de la creacion de una solicitud
	 * @return string
	 */
	public function wizardAction()
	{
		$request = $this->getRequest();
		$error	 = NULL;
		$success = FALSE;
		$response = array();
		$idSolicitudGenerada = 0;
		
		if($request->isPost()){
			
			# Obtenemos el paso del wizard y ejecutamos el wizard con los datos
			$wizard_step = $request->getParam('wizard_step');
			
			$solicitud_wizard = new Kondinero_Application_Solicitud_Wizard();
			
			
			if($wizard_step == Kondinero_Application_Solicitud_Wizard::WIZARD_STEP_FINALIZAR){
				$solicitud_wizard->finishWizard();
				# -
				$idSolicitudGenerada = $solicitud_wizard->getId();
				$response['message'] = "La solicitud se ha creado con exito! El numero de la solicitud es {$idSolicitudGenerada}<br/><br/>¿Deseas dar de alta otra solicitud?";
			} else {
				$solicitud_wizard->runWizard($wizard_step, $request->getParams());	
			}
			
			$success = $solicitud_wizard->isValid();
			
			if($success){
				# - Todo estuvo bien
			} else {
				# - Hubo algun error obtenemos la respuesta
				$success = FALSE;
				$error = $solicitud_wizard->getError();
			}
			
		} else {
			$success = FALSE;
			$error = 'El metodo request de datos debe ser POST';
		}
		
		$response['success'] = $success;
		
		if($success){
			# - Todo va bien!
		} else {
			$response['errors'] = array('reason'=>$error);	
		}
		
		die(json_encode($response));
	}
	
	/**
	 * Accion para obtener los datos para el datagrid
	 */
	public function consultarAction()
	{
		$request = $this->getRequest();
		$start = $request->getParam('start') ? $request->getParam('start') : 0;
		$limit = $request->getParam('limit') ? $request->getParam('limit') : 10;
		
		$solicitud_consulta = new Kondinero_Application_Solicitud_Consulta();
		$registros = $solicitud_consulta->getRegistros($start,$limit);
		
		$data = array('total'=>$solicitud_consulta->getTotalCount()
		             ,'data'=>$registros);
		
		die(json_encode($data));
	}
	
	/**
	 * Devuelve un parse de la solicitud en modo de lectura
	 */
	public function getInformativeAction()
	{
		$request = $this->getRequest();
		$idSolicitud = $request->getParam('id_solicitud');
		$sections = $request->getParam('sections');
		$sections = explode(',',$sections);
		
		$solicitud = new Kondinero_Application_Solicitud($idSolicitud);
		$data = $solicitud->getInformation();
		
		$information = array();
		$this->view->data = $data;
		foreach($sections as $section){
			$this->view->section = $section;
			$information[$section] = $this->view->partial('solicitud.get-informative.phtml', $this->view); 
		}
		
		die(json_encode($information));
	}
	
	/**
	 * Actualiza el estatus de la solicitud
	 */
	public function updateStatusAction()
	{
		$response = array();
		$request = $this->getRequest();
		
		if($request->isPost()){
			$id_solicitud = $request->getParam('id_solicitud');
			$estatus = $request->getParam('estatus');
			
			$solicitud = new Kondinero_Application_Solicitud();
			$rowSolicitud = $solicitud->getRow($id_solicitud);
			
			if($rowSolicitud && $estatus){
				$estatus = (string)$estatus;
				$rowSolicitud->estatus = $estatus;
				$rowSolicitud->save();
				$response['success'] = TRUE;
				$response['message'] = "La solicitud se actualizo con exito al estatus '{$estatus}'";
			} else {
				$response['message'] = 'La solicitud no pudo actualizarse';
			}
		} else {
			$response['message'] = 'No se permiten llamadas por GET';
		}
		
		die(json_encode($response));
	}
	
}