<?php
	/**
	 * Interface para llevar el control de validación y respuesta de errores
	 * @author emoreno
	 */
interface Kondinero_Application_Interface_Validator
{
	public function isValid();
	public function getError();
}