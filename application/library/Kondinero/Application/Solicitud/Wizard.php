<?php
	/**
	 * Clase encargada de llevar acabo el control del wizard de la creación de una solicitud
	 * @autor emoreno
	 */
class Kondinero_Application_Solicitud_Wizard extends Kondinero_Application_Solicitud implements Kondinero_Application_Interface_Validator
{	
	const WIZARD_STEP_FINALIZAR = 'finalizar';
	
	protected $_steps_to_run = array('informacion_solicitante'		=> 'Kondinero_Application_Solicitud_Wizard_Step_Solicitante'
	                                ,'informacion_empleo' 			=> 'Kondinero_Application_Solicitud_Wizard_Step_Empleo'
	                                ,'referencias_personales'		=> 'Kondinero_Application_Solicitud_Wizard_Step_ReferenciasPersonales'
	                                ,'informacion_crediticia'		=> 'Kondinero_Application_Solicitud_Wizard_Step_InformacionCrediticia'
	                                );
	
	# - Validador
	private $_isValid = TRUE;
	private $_error   = NULL;
	# -
	
	private $_wizard_session = NULL;
	
	/**
	 * Inicializa el storage que guardara los datos
	 */
	public function init()
	{
		$this->_wizard_session = new Zend_Session_Namespace(__CLASS__);
		parent::init();
	}
	
	/**
	 * Metodo para llevar acabo el borrado de la sesion del wizard
	 */
	private function _clearWizard()
	{
		Zend_Session::namespaceUnset(__CLASS__);
	}
	
	/**
	 * Metodo encargado del guardado de información para el wizard
	 */
	public function runWizard($wizard_step, $data)
	{
		$wizard_session = $this->_wizard_session;
		$steps_to_run = $this->getStepsToRun();

		if(array_key_exists($wizard_step,$steps_to_run)){
			$wizard_session->$wizard_step = $data;
		} else {
			$this->_isValid = FALSE;
			$this->_error = 'El paso para la forma actual no esta configurado';
		}

	}
	
	/**
	 * Finaliza el wizard guardando toda la información en la BD y notificando al usuario
	 */
	public function finishWizard()
	{
		$wizard = $this->_wizard_session;
		$stepsToRun = $this->getStepsToRun();
		$stepsSaved = array();
		
		try{
			foreach($stepsToRun as $step => $stepClass){
				$stepClassInstance = new $stepClass($wizard->$step);
				$stepsSaved[] = $stepClassInstance->execute();
			}
			
			$dataSolicitud = array();
			$dataSolicitud['fecha_creacion'] = date('Y-m-d H:i:s');
			
			foreach($stepsSaved as $stepSaved){
				
				$info = $stepSaved->getTable()->info();
				$primary = current($info[Zend_Db_Table::PRIMARY]);
				
				$stepIds = $stepSaved->getIds();
				foreach($stepIds as $columnName => $stepId){
					$primary = is_numeric($columnName) ? $primary : $columnName;
					$dataSolicitud[$primary]	= $stepId;
				}
				
			}

			# - Creacion de la solicitud
			$solicitud = new Kondinero_Application_Solicitud();
			$rowSolicitud = $solicitud->getTable()->createRow($dataSolicitud);
			
			if($rowSolicitud->save()){
				$this->_clearWizard();
				$primaryKey = current($solicitud->getTable()->info(Zend_Db_Table::PRIMARY));
				$this->setId($rowSolicitud->$primaryKey);
			} else {
				$this->_isValid = FALSE;
				$this->_error = 'La solicitud no se pudo crear con exito';
			}
			
		} catch(Exception $e){
			$this->_isValid = FALSE;
			$this->_error = $e->getMessage();
		}
	}
	
	/**
	 * Asigna los pasos a ejecutar
	 */
	public function setStepsToRun(array $steps_to_run)
	{
		$this->_steps_to_run = $steps_to_run;
	}
	
	/**
	 * Obtiene los pasos que seran ejecutados
	 */
	public function getStepsToRun()
	{
		return $this->_steps_to_run;
	}
	
	/**
	 * Validador del wizard
	 */
	public function isValid()
	{
		return $this->_isValid;
	}
	
	/**
	 * Devuelve el error que presento el wizard
	 */
	public function getError()
	{
		return $this->_error;
	}
}