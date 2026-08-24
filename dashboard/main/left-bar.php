<aside id="sidebar-left" class="sidebar-left">
				
	<div class="sidebar-header">
		
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">
					<li class="nav-active">
						<a href="index.php">
							<i class="fa fa-home" aria-hidden="true"></i>
							<span>Panel Principal</span>
						</a>
					</li>


					<?php if((isset($_SESSION['isLoggedIn']))){ ?>
					<li class="nav-parent">
						<a>
							<i class="fa fa-user" aria-hidden="true"></i>
							<span>Staff</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="staff-add.php">
									<span class="pull-right label label-primary">add</span>
									<i class="fa fa-plus-square" aria-hidden="true"></i>
									<span>Añadir Staff</span>
								</a>
							</li>
							<li>
								<a href="staff-manage.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-list-ul" aria-hidden="true"></i>
									<span>Administrar Staff</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?>

					<?php if((isset($_SESSION['isLoggedIn']))){ ?>
					<li class="nav-parent">
						<a>
							<i class="fa fa-users" aria-hidden="true"></i>
							<span>Usuarios</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="user-add.php">
									<span class="pull-right label label-primary">add</span>
									<i class="fa fa-plus-square" aria-hidden="true"></i>
									<span>Añadir Usuario</span>
								</a>
							</li>
							<li>
								<a href="user-list.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-list-ul" aria-hidden="true"></i>
									<span>Administrar Usuario</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?>

					<?php if((isset($_SESSION['isLoggedIn']))){ ?>
					<li class="nav-parent">
						<a>
							<i class="fa fa-table" aria-hidden="true"></i>
							<span>Mesas</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="table-add.php">
									<span class="pull-right label label-primary">add</span>
									<i class="fa fa-plus-square" aria-hidden="true"></i>
									<span>Añadir Mesas</span>
								</a>
							</li>
							<li>
								<a href="table-list.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-eye" aria-hidden="true"></i>
									<span>Ver Mesas</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?>

					<?php if((isset($_SESSION['isLoggedIn']))){ ?>
					<li class="nav-parent">
						<a>
							<i class="fa fa-cutlery" aria-hidden="true"></i>
							<span>Menu</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="menu-add.php">
									<span class="pull-right label label-primary">add</span>
									<i class="fa fa-plus-square" aria-hidden="true"></i>
									<span>Añadir Platillos</span>
								</a>
							</li>
							<li>
								<a href="menu-list.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-eye" aria-hidden="true"></i>
									<span>Ver Platillos</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?>

					<?php if((isset($_SESSION['isLoggedIn']) )){ ?>
					<li class="nav-parent">
						<a>
							<i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
							<span>Reservas</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="booking-list.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-eye" aria-hidden="true"></i>
									<span>Verificar</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?> 

					<?php if((isset($_SESSION['isLoggedIn']) )){ ?>
					<li class="nav-parent">
						<a>
							<i class="glyphicon glyphicon-bullhorn" aria-hidden="true"></i>
							<span>Pedidos</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="food-orderList.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-eye" aria-hidden="true"></i>
									<span>Orden de Comida</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?> 

					<?php if((isset($_SESSION['isLoggedIn']) )){ ?>
					<li class="nav-parent">
						<a>
							<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
							<span>Mensages</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="message.php">
									<span class="pull-right label label-info">list</span>
									<i class="fa fa-envelope" aria-hidden="true"></i>
									<span>Ver Mensajes</span>
								</a>
							</li>
						</ul>
					</li>
					<?php } ?> 
					

				</ul>
			</nav>

			<hr class="separator" />
		</div>

	</div>

</aside>


