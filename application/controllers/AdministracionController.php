<?php

	/**
	 * Controlador de la administración del sistema
	 * @author emoreno
	 */

class AdministracionController extends Zend_Controller_Action
{	
	/**
	 * Accion principal del controlador
	 */
	public function indexAction()
	{
		$this->_helper->redirector('wizard');
	}
	
	/**
	 * Carga de la información para INFO963, BNP y Credito Real
	 * @return string
	 */
	public function importAction()
	{
		$this->_helper->layout()->disableLayout();
		
		$request = $this->getRequest();
		$success = FALSE;
		$error   = FALSE;
		
		if($request->isPost()){
			
			$file_adapter = new Zend_File_Transfer_Adapter_Http();
			$importType = $request->getParam('importType');
			
			switch($importType){
				case 'info963':
					$extension = 'csv';
					break;
				case 'bnp':
					$extension = 'txt';
					break;
				default:
					$error = 'No se ha configurado el importType en la forma';
			}
			
			if($error){
				# -
			} else {

				$file_adapter->addValidator('Extension',false,array($extension));
				$file_adapter->getValidator('Extension')->setMessage("El archivo '%value%' tiene una extension incorrecta para el tipo de archivo '{$importType}'",Zend_Validate_File_Extension::FALSE_EXTENSION);
				
				$file = current($file_adapter->getFileInfo());
				$file_name = @$file['name'];
				
				if(   $file_adapter->isUploaded($file_name)
				   && $file_adapter->receive($file_name)){
						# -
						$importer = Kondinero_Application_Administracion_Importacion_Factory::getImporter($importType);
						
						$success = $importer->isValid();
						if($success){
							$importer->execute($file_adapter);

							$success = $importer->isValid();
							if($success){
								# -
								
								
							} else {
								$error = $importer->getError();
							}

						} else {
							$error = $importer->getError();
						}
										
				} else {
					$error = current($file_adapter->getMessages());
				}
			}

		} else {
			$error = 'Operacion GET no permitida';
		}
		
		$response['success'] = $success;
		
		if($success){
			# - Todo va bien!
		} else {
			$response['errors'] = array('reason'=>$error);	
		}
		
		die(json_encode($response));
	}
	
}

/*
# CREDITO
saldos_cuenta									codigo_cuenta
saldos_consecutivo								consecutivo
saldos_operacion								codigo_dap
saldos_plazo									plazo
saldos_emision									fecha_emision
saldos_cobrado									fecha_cobro
saldos_importe									* importe
saldos_costo									monto_credito
saldos_cargo_abonos								* cargo_abonos
saldos_saldo									* saldo
saldos_dv (digito_verificador)					* digito_verificador
saldos_fecha_ped (pendiente)					* fecha_pendiente
saldos_nomina									numero_nomina
saldos_banco									codigo_banco
saldos_fum (fecha_ultimo_pago)					* fecha_ultimo_pago
saldos_ultimo_pago								ultimo_pago
saldos_financiamiento							* saldo_financiamiento
saldos_iva										* iva
saldos_seguro									* seguro
saldos_comision									* comision
saldos_referencia								* referencia
saldos_quincena									* saldos_quincena
saldos_porcentaje								* porcentaje
saldos_pagada									* pagada
AUTORIZADO_10									* autorizado
saldos_ultimo_pago_importe						* ultimo_pago_importe
saldos_desc_quin (descuento quincenal)			abono_quincenal
saldos_fecha									* fecha
dif_fec											* dif_fec
saldos_quin_pen (quincenas pendientes)			* quincena_pendiente
saldos_qc										* qc
saldos_mc										* mc
saldos_a										* a
saldos_desctot (descuento total)				* descuento_total
saldos_credt									
saldos_credfu									* credfu
*/

/*
# CLIENTE
* saldos_nombre
* saldos_rfc
* saldos_nacimiento
rfc_real
* saldos_telefono
id_municipio
*/

/*
# SOLICITUD
id_sucursal
id_ruta
id_institucion
id_financiera
id_documento
*/

/*
# DOCUMENTO *
saldos_documento 
*/

/*
# VENDEDOR
saldos_vendedor
*/