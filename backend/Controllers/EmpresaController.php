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
        
        public function getDocumentsinDateEspecific(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getDocumentsinDateEspecificData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function getDocumentsFromEachCompany(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getDocumentsFromEachCompanyData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function getDocumentsFailedMoreThanThree(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getDocumentsFailedMoreThanThree();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function getDocumentsOutOfRange(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getDocumentsOutOfRangeData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function getDocumentstotalInvoice(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getDocumentstotalInvoiceData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function getNumberCompleteRepeated(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::getNumberCompleteRepeatedData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}

        
        public function postSaveNewDocument(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::postSaveNewDocumentData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function putSaveDocumentId(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::putSaveDocumentIdData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}
        
        public function deleteDocumentId(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = EmpresaModel::deleteDocumentIdData();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);

	}

}
 ?>