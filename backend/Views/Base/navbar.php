
<!-- Se debe añadir un eco en php o texto de toda la ruta raiz para que cargue bien sino, añade el texto que se indique a la ruta actual... -->
<div id="title" align="center" style="margin-top: 30px;"><a href="<?=URL_ROOT?>asd" >Vista principal</a></div>
<!-- Al final borrar lo anterior, ya que es solo un adorno para indicar que vista es.. -->

<style>
	.nav-link, .navbar-brand{
		color: white;
	}
	.nav-link:hover, .navbar-brand:hover{ color:Gainsboro; }





</style>
<div class="container ">
<nav class="navbar navbar-expand-lg " style="background: linear-gradient(40deg,#fc6262,#DC143C) !important">
	<div class="container-fluid" >
		<a class="navbar-brand" href="#">Inicio</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarColor02">
			<ul class="navbar-nav mr-auto mb-2 mb-lg-0">
				<li class="dropdown-menu">
					<a class="nav-link" aria-current="page" href="#">Usuarios</a>
				</li>
				 <!-- Navbar dropdown -->
		        <li class="nav-item dropdown">
					<a
						class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">Usuarios
					</a>
					<!-- Contenido navbar dropdown -->
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a href="" class="dropdown-item">Administrar Usuarios</a></li>
						<li><a href="" class="dropdown-item">Administrar Roles</a></li>
					</ul>
				</li>

				 <li class="nav-item">
					<a class="nav-link" href="#">Proveedores</a>
				</li>

				 <!-- Navbar dropdown -->
		        <li class="nav-item dropdown">
					<a
						class="nav-link dropdown-toggle "
						href="#"
						id="navbarDropdown"
						role="button"
						data-toggle="dropdown"
						aria-expanded="false"
						>Ingredientes
					</a>
					<!-- Contenido navbar dropdown -->
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a href="" class="dropdown-item">Administrar Ingredientes</a></li>
						<li><a href="" class="dropdown-item">Administrar Categorías</a></li>
					</ul>
				</li>

				 <!-- Navbar dropdown -->
		        <li class="nav-item dropdown">
					<a
						class="nav-link dropdown-toggle "
						href="#"
						id="navbarDropdown"
						role="button"
						data-toggle="dropdown"
						aria-expanded="false"
						>Productos
					</a>
					<!-- Contenido navbar dropdown -->
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a href="" class="dropdown-item">Administrar Productos</a></li>
						<li><a href="" class="dropdown-item">Administrar Categorías</a></li>
					</ul>
				</li>

				<li class="nav-item">
					<a href="#" class="nav-link">Clientes</a>
				</li>

				<!-- Navbar dropdown -->
		        <li class="nav-item dropdown">
					<a
						class="nav-link dropdown-toggle "
						href="#"
						id="navbarDropdown"
						role="button"
						data-toggle="dropdown"
						aria-expanded="false"
						>Ventas
					</a>
					<!-- Contenido navbar dropdown -->
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a href="" class="dropdown-item">Administrar Ventas</a></li>
						<li><a href="" class="dropdown-item">Generar Venta</a></li>
					</ul>
				</li>

				<!-- Navbar dropdown -->
		        <li class="nav-item dropdown">
					<a
						class="nav-link dropdown-toggle "
						href="#"
						id="navbarDropdown"
						role="button"
						data-toggle="dropdown"
						aria-expanded="false"
						>Reabastecer
					</a>
					<!-- Contenido navbar dropdown -->
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a href="" class="dropdown-item">Administrar Solicitudes</a></li>
						<li><a href="" class="dropdown-item">Generar Solicitud</a></li>
					</ul>
				</li>

			</ul>




			<ul class="navbar-nav">
				<!-- Icon dropdown -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
						<i class="united kingdom flag m-0"></i>
					</a>
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li>
							<a class="dropdown-item" href="#"><i class="united kingdom flag"></i>English
								<i class="fa fa-check text-success ml-2"></i></a>
							</li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<a class="dropdown-item" href="#"><i class="poland flag"></i>Polski</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="china flag"></i>中文</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="japan flag"></i>日本語</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="germany flag"></i>Deutsch</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="france flag"></i>Français</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="spain flag"></i>Español</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="russia flag"></i>Русский</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"><i class="portugal flag"></i>Português</a>
							</li>
						</ul>
					</li>
					<!-- Avatar -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
							<img src="https://mdbootstrap.com/img/Photos/Avatars/img (31).jpg" class="rounded-circle" height="22" alt="" loading="lazy">
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<li><a class="dropdown-item" href="#">Perfil</a></li>
							<li><a class="dropdown-item" href="#">Ajustes</a></li>
							<li><a class="dropdown-item" href="#">Salir </a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
</nav>






</div>


