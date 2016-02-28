<html lang="pt-br">
	<head>
		<title>
			Gerenciador de tarefas
		</title>

		<meta charset="utf-8">
		<meta name="description" content="Gerendiador de tarefas">
		<meta name="keywords" content="Gerenciador de tarefas, organização, trabalhos escolares">
		<meta name="author" content="Douglas Oliveira">
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">

		<link rel="stylesheet" href="/app/webroot/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/app/webroot/bootstrap/css/datepicker/bootstrap-datepicker.min.css">
		<link rel="stylesheet" href="/app/webroot/css/estilos.css">
		<link rel="stylesheet" href="/app/webroot/datatable/css/jquery.dataTables.min.css">
		<link rel="shortcut icon" href="/app/webroot/img/favicon.png">
		<script src="/app/webroot/js/jquery.min.js"></script>
		<script src="/app/webroot/datatable/js/jquery.js"></script>
		<script src="/app/webroot/datatable/js/jquery.dataTables.min.js"></script>
		<script src="/app/webroot/bootstrap/js/bootstrap.min.js"></script>
		<script src="/app/webroot/bootstrap/js/bootstrap-datepicker.min.js"></script>
		<script src="/app/webroot/bootstrap/js/locales/bootstrap-datepicker.pt-BR.min.js"></script>
		<script src="/app/webroot/js/jquery.maskedinput.min.js"></script>
		<script src="/app/webroot/js/scripts.js"></script>
	</head>

	<body>
		<?php require_once('navbar-menu.php'); ?>
		
		<div class="container" id="container">
			<?php 
				if (isset($_SESSION['alert'])) {
					$alert = $_SESSION['alert'];
					
					if (isset($_SESSION['user'])) {
						$alert = "<div class='col-md-2'></div><div class='col-md-8'>{$_SESSION['alert']}</div>";
					}

					echo $alert;
					unset($_SESSION['alert']);
				}
			?>