<?php
	/**
	 * Helper para obtener dinamicamente el arbol de sistema para los menus, este se adapta
	 * a los permisos que cada rol de usuario tenga sobre estos elementos
	 * @author emoreno
	 */
class Kondinero_View_Helper_TreePanelMenu extends Zend_View_Helper_Abstract
{
	const ATTRIBUTE_CHILDREN = 'children';
	const ATTRIBUTE_ID       = 'id';
	/**
	 * Obtiene la respuesta JSON dinamicamente en base al rol del usuario
	 */
	public function treePanelMenu()
	{
		$treePanelMenu  = array();
		$showPanelByRol = array();
		$menu = new Zend_Config_Ini(APPLICATION_PATH.'/configs/main/config.ini','menu');
		
		if($menu instanceOf Zend_Config){
			# treePanel
			if(isset($menu->treepanel)){
				$treePanelMenu = $menu->treepanel->toArray();
			} else {
				# - El menu no esta configurado
				trigger_error('No se ha configurado el treePanel para el menu general',E_USER_ERROR);	
			}
			# Permisos
			if(isset($menu->showPanelByRol)){
				$showPanelByRol = $menu->showPanelByRol->toArray();
			} else {
				# - El menu no esta configurado
				trigger_error('No se han definido los permisos por roles para mostrar el treePanel',E_USER_ERROR);	
			}
			
			# Verificamos identidad y tomamos el rol que tiene asignado
			$auth = Zend_Auth::getInstance();
			$rol = 'guest';
			
			if ($auth->hasIdentity()){
				$storage = $auth->getStorage()->read();
				$rol = $storage->rol;
			} else {
				# --
			}
			
			# Tomamos la configuración para este rol, que seran los paneles que se mostraran
			$resourcesToCheck = array();
			$applyFilter = TRUE;
			
			if(isset($showPanelByRol[$rol])){
				$applyFilter = $showPanelByRol[$rol] === '*' ? FALSE : $applyFilter; 
				$resourcesToCheck = explode(',',$showPanelByRol[$rol]);
			} else {
				# -
			}
			
			# Filtrado si aplica!, no aplicara cuando tengamos un * (todos) para visualizar recursos
			$applyFilter && $this->_filter($treePanelMenu,$resourcesToCheck);
			
		} else {
			trigger_error('No se ha creado el archivo de configuración para el menu',E_USER_ERROR);	
		}
		
		return $treePanelMenu;
	}
	
	/**
	 * Lleva acabo un filtrado de datos tomando como base un arreglo de treepanel y lo compara
	 * contra los recursos que tenga asignados el usuario en sesion
	 */
	private function _filter(array &$treePanelMenu, array $resourcesToCheck = array())
	{
		$resourcesToCheck = array_unique($resourcesToCheck);
		
		# Obtenemos todos los recursos del treePanel actual
		$treePanelResources = array();
		
		foreach($treePanelMenu as $branch => $data){
			foreach($data[self::ATTRIBUTE_CHILDREN] as $child){
				$treePanelResources[] = $child[self::ATTRIBUTE_ID];
			}
		}
		
		# Seguidamente obtenemos la diferencia de los recursos actuales y los que se verificaran
		# de esta manera podremos eliminar del treePanelMenu los que no deben ser visibles
		$resourcesToRemove = array_diff($treePanelResources,$resourcesToCheck);
		
		# Procedemos con el filtrado
		foreach($treePanelMenu as $branch => &$data){
			# Recorremos todas las ramas y de cada rama tomamos las hojas
			# por referencia para poderlas eliminar despues si necesitan removerse
			$children = &$data[self::ATTRIBUTE_CHILDREN];
			foreach($children as $index => $leaf){
				# Recorremos cada uno de los children y si sera necesario removerlo lo eliminamos
				if(in_array($leaf[self::ATTRIBUTE_ID],$resourcesToRemove)){
					unset($children[$index]);	
				} else {
					# -
				}
				
			}
		}
		
		# En este punto ya solo removemos las ramas vacias
		foreach($treePanelMenu as $branch => &$data){
			$children = &$data[self::ATTRIBUTE_CHILDREN];
			if(empty($children)){
				unset($treePanelMenu[$branch]);
			} else {
				# Tiene almenos una hoja, conservamos la rama ;)
			}
		}
		
		# Patch para reiniciar los indices de las ramas y hojas dado a que EXTJS tiene un bug
		# de indices, siempre tiene que empezar en 0 y no permite mostrar datos con indices iniciales arriba de 0
		$realTreePanelMenu = array_values($treePanelMenu);
		$branchChildren = array();
		foreach($realTreePanelMenu as $branch => $dataLeaf){
			$realChildren = array_values($dataLeaf[self::ATTRIBUTE_CHILDREN]);
			$branchChildren[$branch] = $realChildren;
		}
		foreach($branchChildren as $branch => $child){
			$realTreePanelMenu[$branch][self::ATTRIBUTE_CHILDREN] = $child; 
		}
		$treePanelMenu = $realTreePanelMenu;
	}
	
}