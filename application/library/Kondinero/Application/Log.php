<?php
/** Clase Log para almacenar historial
 * @author fortigoza*/
class Kondinero_Application_Log
	{
     public static  function getLog($descripcion)
     {
     	
     	
     	$db = Zend_Db_Table::getDefaultAdapter();
		$columnMapping = array('nombre' => 'nombre','id_usuario' => 'id_usuario','descripcion' => 'descripcion');
		$writer = new Zend_Log_Writer_Db($db, 'historial',$columnMapping);
								
		$log = new Zend_Log($writer);
		$log->setEventItem('id_usuario', $usuario= Zend_Auth::getInstance()->getIdentity()->id_usuario);
		$log->setEventItem('nombre', $nombre= Zend_Auth::getInstance()->getIdentity()->login);
		$log->setEventItem('descripcion', $descripcion);
		$log->log('esta es una prueba', 1);
     }

}