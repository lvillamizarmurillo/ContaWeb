<?php 

/**
 * 
 */

require_once 'Models/UserModel.php';

class UsersController extends UserModel
{
	public function renderUser(){//v1
		//objeto de datos que retornemos del modelo para meter en la vista. ej $obj=$this->methodShow();
		//se guarda en una variable que llamaremos en la vista cuando recorramos sus datos.

		$obj_users = self::getUser();	//atención con el self que toma esta clase y no la padre, en caso de que exista ese mismo método aquí se sobreescribe!		
		var_dump($obj_users);		
		require_once 'Views/Modules/Users/index.php';




	}

	public function addUserSet($nombre, $apellido)//v1 pasar variables al modelo para que las cargue en sus atributos
	{//deberíamos poner métodos donde se purifique los post y get por seguridad.
		$this->usu_nombre = $nombre;
		$this->usu_apellido = $apellido;
		$this->addUser(); //parent::addUser() servirá?
		//redirect();
	}

}


if(isset($_GET['dir']) && $_GET['dir']=='users'){//v1 default acción
	$instanciapeliculas = new UsersController();
	$instanciapeliculas->renderUser();
}

if(isset($_POST['exe']) && $_POST['exe']=='add'){//v1 default acción
	$instanciapeliculas = new UsersController();
	$instanciapeliculas->addUserSet( //nota: con un row Count()>0 deberíamos tomarlo como que si agregó. ya que el método prepare() devuelve TRUE en caso de éxito o FALSE en caso de error.
		$_POST['nombre'],
		$_POST['apellido']
	);
}





 ?>