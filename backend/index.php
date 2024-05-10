


<!-- ----------------------------------------------  -->
<?php 
	if(isset($_GET['url']))
	{
		echo $_GET['url'];
		//header("location: index.php?dir=render");
	}


	//echo "Hola interacpedia!";

	//echo "<br><br>".$_GET['dir']."<BR>";

	
	if(isset($_GET['dir']) ){
		$url = !empty($_GET['dir']) ? $_GET['dir'] : 'home';
	}else{ $url = 'e404'; } //fixer de poner index.php?asdfg que no lo captura en dir y mandaba a home, como si se reescribiera wtf!

	$array_url = explode("/", strtolower($url));//guarda un array de elementos donde cada uno es separado por / y convierte la string a minusculas para compatibilidad con url mayusculas, minusculas o mezcladas.
	
	echo $url."<br><br>"; //imprime el valor de la url después de la carpeta raiz como se setteo en .htaccess o a home si es vacío por el if que setteamos.
	//print_r( $array_url); //imprime el tipo de objeto con su contenido.

	//$controller = $array_url[0];
	//$controller_route = "Controllers/".$controller."Controller.php";

	require_once 'Config/config.php';//configuraciones de la app como de la bd.
	

	$_GET['dir'] = $array_url[0]; //setteo la variable get ya que esta se requiere como una get en el controlador, no guardarla en una variable, sino habría que pasarla al controlador en vez de la get original.
	//echo "Es:".($test)."2";
	echo "Ess: ".($_GET['dir'])."<br>";

	//$routes = $array_url[0]."/".$array_url[1];
	//$routes = isset($array_url[1]) ? $array_url[0]."/".$array_url[1] : $array_url[0];
	$routes = isset($array_url[0]) ? $array_url[0] : 'home';
	
	if(isset($array_url[1]) && !empty($array_url[1])){
		if ($array_url[1] == "edit" || $array_url[1] == "conexions") {
			echo "<br>Se está enviando un método de visualización llamado {$array_url[1]} ! <br>";//en el controller la get manda al editRender!
			$routes = isset($array_url[3]) ? $routes.$array_url[3] : $routes;//fix más de 3 valores (subvistas)
			if (isset($array_url[2]) && !empty($array_url[2]) ){
				echo "<br>Se está enviando el parámetro {$array_url[2]} <br>";
			}else{ echo "<br>Se envió método de visualización sin identificador!!<br>";}
		}else { echo "<br>cargando una vista por método base..<br>";
				echo "<br>Se está enviando el parámetro {$array_url[1]} <br>"; 
				$routes = isset($array_url[2]) ? $routes.$array_url[2] : $routes;//fix más de 3 valores (subvistas)
				}
		
	}else{echo "<br>cargando una vista por método base sin parámetros..<br>";}

	//NOTA: LO RECOMENDABLE SERÍA Q SE CAPTURE EL MÉTODO DE VISUALIZACIÓN Y NO SE CREE OTRO CASE SINO SE MANDE EN GET PARA Q EL CONTROLADOR LA MUESTRE, ASÍ PUEDO CAPTURAR LA CANTIDAD DE "SUBVISTAS" Y EN EL CONTROLADOR ELIJO MOSTRARLA Y NO TENGO QUE CREAR UN MONTÓN DE SUBVISTAS A PURO CASE...


	echo "<br>  ".$routes."<br>  ";

	//*-$routes = isset($array_url[3]) ? $routes.$array_url[3] : $routes;//fix más de 3 valores (subvistas)
	//pendiente añadir al fix para envios sin subvistas, el anterior es para subvistas!!

	echo "<br>  ".$routes."<br>  ";
	//MAPEADO
	switch ($routes) {
		case 'home':		
			//require_once "Controllers/HomeController.php";			
			break;
		
		case 'users':
		//case 'users/edit':// Se cancela ya que se mostrará subvistas mediante el controlador
		case 'usuarios': //usar json para el lenguaje que también esté en rutas url simulada.
			$_GET['dir'] = "users";
			require_once "Controllers/UsersController.php";			
			break;
		case 'user-roles':		
			require_once "Controllers/UsersController.php";			
			break;

		case 'suppliers':
			require_once "Controllers/SuppliersController.php";			
			break;		
		case 'ingredients':
			require_once "Controllers/UsersController.php";
			break;

		case 'ingredient-categories':
			require_once "Controllers/UsersController.php";			
			break;
		case 'products':
			require_once "Controllers/UsersController.php";			
			break;		
		case 'product-categories':
			require_once "Controllers/UsersController.php";			
			break;

		case 'sales':
			require_once "Controllers/UsersController.php";			
			break;
		case 'generate-sales':
			require_once "Controllers/UsersController.php";			
			break;	
		case 'ingredient-request-list':
			require_once "Controllers/UsersController.php";			
			break;
		case 'ingredient-request':
			require_once "Controllers/UsersController.php";			
			break;
		default:
			echo "Error 404|Página no encontrada.";
			break;
	}


/*	if(file_exists($controller_route))
	{
		
		//echo "es: ".$controler_route;
		//require_once $controller_route;
		
		//echo "raiz: ".dirname( __FILE__ )."<br>"; //solo __DIR__ es igual creo,..hmm no me convence que sea todo el directorio raiz de carpetas, ni seguro creo, de momento.. para obtener la ruta raiz.
	}*/
		

?>

