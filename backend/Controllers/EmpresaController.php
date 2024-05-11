<?php 

/**
 * 
 */

require_once 'Models/EmpresaModel.php';

class EmpresaController extends EmpresaModel
{
	public function getDocumentsFailed(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getDocumentsFailedData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}

}
 ?>