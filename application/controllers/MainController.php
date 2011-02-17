<?php

	/**
	 * Controlador default para el redireccionamiento inicial 
	 * @author emoreno
	 */
class MainController extends Zend_Controller_Action
{	
	/**
	 * Accion principal para mostrar la pagina general
	 */
	public function indexAction()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
								$columnMapping = array('nombre' => 'nombre','id_usuario' => 'id_usuario');
								
								$writer = new Zend_Log_Writer_Db($db, 'historial',$columnMapping);
								
								$log = new Zend_Log($writer);
								$log->setEventItem('id_usuario', $usuario=Zend_Auth::getInstance()->getIdentity()->id_usuario);
								$log->setEventItem('nombre', 'Inicio Session');
								$log->log('esta es una prueba', 1);
								
		$this->_helper->layout()->enableLayout();
	}
	
	/**
	 * Devuelve el arbol del menu generado dinamicamente
	 * @return string
	 */
	public function menuAction()
	{
		$treepanel = $this->view->treePanelMenu();
		die(json_encode($treepanel));
	}
}