<?php
	/**
	 * Clase de verificación mediante roles
	 * @author emoreno
	 */
class Kondinero_Acl extends Zend_Acl
{

	/**
	 * Constructor de la clase
	 */
    public function __construct()
    {
        //--------------------
        // Roles
        // -------------------
        $this->addRole('guest');
        $this->addRole('AUXILIAR_CREDITO', 'guest');
        $this->addRole('GERENTE_SUCURSAL', 'AUXILIAR_CREDITO');
        $this->addRole('JEFE_CREDITO', 'GERENTE_SUCURSAL');
        $this->addRole('GERENTE_CREDITO','JEFE_CREDITO');
        $this->addRole('GERENTE_OPERACION','GERENTE_CREDITO');
        $this->addRole('ADMINISTRADOR');

        // -------------------
        // Default Resource
        // -------------------
        $this->addResource('index');
        $this->addResource('login','index');
        $this->addResource('main');
        $this->addResource('solicitud');
        $this->addResource('administracion');
        $this->addResource('report');
        $this->addResource('catalog');
        $this->allow('guest', 'login');
        $this->allow('ADMINISTRADOR');
        $this->allow('GERENTE_OPERACION');
        $this->allow('GERENTE_CREDITO');
        $this->allow('JEFE_CREDITO');
        $this->allow('GERENTE_SUCURSAL');
        $this->allow('AUXILIAR_CREDITO');
    }    
    
}