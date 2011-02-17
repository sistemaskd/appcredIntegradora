<?php
	/**
	 * Deniega al usuario realizar mas de 1 request x cada 2 segundos al autentificarse
	 * ! Security !
	 * @author emoreno
	 */
class Kondinero_Controller_Plugin_Antiflood extends Zend_Controller_Plugin_Abstract
{
	private $_session;
	
	/**
	 * Inicializamos la sesion
	 */
	public function __construct()
	{
		$this->_session = new Zend_Session_Namespace(__CLASS__);
	}
	
	/**
	 * En base al numero de requests, concedemos o denegamos accesos
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if(   $request->getControllerName()=='login'
		   && $request->getActionName()=='process'){
			$die = FALSE;
			if(isset($this->_session->lastSessionRequest)){
				# Verificamos si se denego su acceso para que se espere
				if(time() - $this->_session->waitingTime > 0){
					$this->_session->waitingTime = 0;
					# Ya accedio a esta pagina; verificamos tiempo de peticion
					if($this->_session->lastSessionRequest > time() - 2){
						# Denegado, ahora te esperas 1 minuto
						$this->_session->waitingTime = time() + 60;
						$die = TRUE;
					} else {
						# Concedido
					}	
				} else {
					$die = TRUE;
				}
			} else {
				# --
			}
			
			if($die){
				$waitingTime = $this->_session->waitingTime - time();
				$reason = "Sistema con Anti-flood habilitado, espera {$waitingTime} segundos.";
				$response = array('errors'=>array('reason'=>$reason));
				die(json_encode($response));
			} else {
				# -
			}
			
			# Guardamos el tiempo
			$this->_session->lastSessionRequest = time();
		} else {
			# Let's go on...
		}
	}
}