<!DOCTYPE html>
<html lang="pt_BR">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Wizard</title>

	<!-- Bootstrap -->
	<link href="public/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="public/libs/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="public/js/html5shiv.min.js"></script>
      <script src="public/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		            <span class="sr-only">Toggle Navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		        	<span class="icon-bar"></span>
		        </button>
				<a target="_blank" class="navbar-brand" href="https://github.com/weverkley/Simple-MVC-Framework">Simple-MVC-Freamework</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right"></ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container-fluid -->
	</nav>

	<div class="container">
		<?php $error = false; if (!isset($_GET['step'])): ?>
			<div class="col-md-6 col-md-offset-3">
				<ul class="list-group">
					<?php if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())): ?>
						<li class="list-group-item">Rewrite Module <span class="badge"><i class="fa fa-check"></i></span></li>
					<?php elseif (isset($_SERVER['IIS_UrlRewriteModule'])): ?>
						<li class="list-group-item">Rewrite Module <span class="badge"><i class="fa fa-check"></i></span></li>
					<?php else: $error = true; ?>
						<li class="list-group-item">Rewrite Module <span class="badge"><i class="fa fa-times"></i></span></li>
					<?php endif; ?>


					<?php if (class_exists('PDO')): ?>
						<li class="list-group-item">PDO Extension <span class="badge"><i class="fa fa-check"></i></span></li>
					<?php else: $error = true; ?>
						<li class="list-group-item">PDO Extension <span class="badge"><i class="fa fa-times"></i></span></li>
					<?php endif; ?>

					<?php if (shell_exec('mysql -V') != ''): ?>
						<li class="list-group-item">MySQL Extension <span class="badge"><i class="fa fa-check"></i></span></li>
					<?php else: $error = true; ?>
						<li class="list-group-item">MySQL Extension <span class="badge"><i class="fa fa-times"></i></span></li>
					<?php endif; ?>

					<?php if (is_writable(ROOT.DS.'config'.DS)): ?>
						<li class="list-group-item">Writable config file <span class="badge"><i class="fa fa-check"></i></span></li>
					<?php else: $error = true; ?>
						<li class="list-group-item">Writable config file <span class="badge"><i class="fa fa-times"></i></span></li>
					<?php endif; ?>
				</ul>
				<?php if (!$error): ?>
					<a class="btn btn-primary" href="?step=1">Next Step</a>
				<?php else: ?>
					<div class="alert alert-danger" role="alert">
						<strong>Error!</strong> Fix errors before proceed.
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if (isset($_GET['step']) && $_GET['step'] == 1 && $error == false): ?>
			<div class="col-md-6 col-md-offset-3">
				<?php if (isset($_GET['mysql_error'])): ?>
					<div class="alert alert-danger alert-dismissable" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Error!</strong> Cannot connect to your database, verify your settings.<br>
						<?= ($_GET['message'])? $_GET['message']: '' ?>
					</div>
				<?php endif; ?>
				<form method="GET">
				<div class="panel panel-default">
					<div class="panel-body">
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Debug mode</label>
							<select class="form-control" name="DEBUG_MODE" required>
								<option value="" selected disabled>Select one option</option>
								<option value="true">True</option>
								<option value="true">False</option>
							</select>
							<small class="text-muted">Exposes/omit erros on the screen.</small>
						</fieldset>
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Website Location</label>
							<input name="WEBSITE_URL" type="text" class="form-control" placeholder="http://localhost/Simple-MVC-Framework/" value="http://localhost/Simple-MVC-Framework/" required>
							<small class="text-muted">Url of your website.</small>
						</fieldset>
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Hide default controller</label>
							<select class="form-control" name="HIDE_DEFAULT_CONTROLLER" required>
								<option value="" selected disabled>Select one option</option>
								<option value="true">True</option>
								<option value="true">False</option>
							</select>
							<small class="text-muted">Hide default controller from url Ex: website/home/index will be website/index.</small>
						</fieldset>
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Database Host</label>
							<input name="DB_HOST" type="text" class="form-control" placeholder="127.0.0.1" value="127.0.0.1" required>
							<small class="text-muted">Host of your database.</small>
						</fieldset>
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Database User</label>
							<input name="DB_USER" type="text" class="form-control" placeholder="root" value="root" required>
							<small class="text-muted">User of your database.</small>
						</fieldset>
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Database Password</label>
							<input name="DB_PASSWORD" type="text" class="form-control" placeholder="123456" value="123456" required>
							<small class="text-muted">Password of your database.</small>
						</fieldset>
						<fieldset class="form-group">
							<label for="exampleInputEmail1">Database Name</label>
							<input name="DB" type="text" class="form-control" placeholder="test" value="test" required>
							<small class="text-muted">Name of your database.</small>
						</fieldset>
					</div>
				</div>
				<input type="hidden" name="step" value="2">
				<button type="submit" class="btn btn-primary">Next step</button>
			</form>
			</div>
		<?php endif; ?>

		<?php
			if (isset($_GET['step']) && $_GET['step'] == 2 && $error == false):
				try {
					new Pdo('mysql:host='.$_GET['DB_HOST'].';dbname='.$_GET['DB'].';charset=utf8', $_GET['DB_USER'], $_GET['DB_PASSWORD']);
				} catch (Exception $e) {
					die(header('Location: ?step=1&mysql_error=true&message='.$e->getMessage()));
				}

$code = "<?php
//define a localidade
setlocale(LC_TIME, 'pt_BR.utf8');

//define o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// debug mode will print erros, if false erros will be saved in log folder
define('DEBUG_MODE', ".$_GET['DEBUG_MODE'].");

// database configuration
define('DB_HOST', '".$_GET['DB_HOST']."');
define('DB_USER', '".$_GET['DB_USER']."');
define('DB_PASSWORD', '".$_GET['DB_PASSWORD']."');
define('DB', '".$_GET['DB']."');

// default controller
define('DEFAULT_CONTROLLER', 'home');
// hide default controller from url (true, false) EX: /index, /about
define('HIDE_DEFAULT_CONTROLLER', ".$_GET['HIDE_DEFAULT_CONTROLLER'].");

// fill with the folder name followed by a / or just leave empty.
define('BASE_URL', '".$_GET['WEBSITE_URL']."');";

				$target = ROOT.DS.'config'.DS.'config.php';

				if (!file_exists($target)) file_put_contents($target, $code);
				chmod($target, fileperms($target) | 128 + 16 + 2);

			?>
				<div class="col-md-6 col-md-offset-3">
					<div class="jumbotron">
						<h2>Your config file have been created, now you can proceed to your website.</h2>
					</div>
					<a class="btn btn-primary" href="<?= $_GET['WEBSITE_URL'] ?>">Home Page</a>
				</div>
			<?php endif; ?>

	</div>

	<footer>
		<div class="container">
			<p>
				Contribute to this project
				<a target="_blank" href="https://github.com/weverkley/Simple-MVC-Framework">Simple-MVC-Framework.</a>
			</p>
		</div>
	</footer>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="public/libs/jquery/dist/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="public/libs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>
