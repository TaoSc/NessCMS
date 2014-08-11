<?php
	if (pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME) === 'install.php')
		header('Location: ./');
	elseif (isset($_POST['site_name'])) {
		if (PHP_VERSION_ID < 50400)
			die('Your version of PHP is too old. Please use PHP 5.4 at least');

		if (!file_exists($configFile)) {
			if (isset($_POST['host']) AND isset($_POST['name']) AND isset($_POST['user']) AND isset($_POST['pass']))
				file_put_contents($configFile, '<?php $dbHost = \'' . $_POST['host'] . '\';$dbName = \'' . $_POST['name'] . '\';$dbUser = \'' . $_POST['user'] . '\';$dbPass = \'' . $_POST['pass'] . '\';');
			else
				die('<a href="./">Please retry.</a>');
		}
		include $configFile;

		try {
			$db = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8', $dbUser, $dbPass);
		}
		catch (Exception $error) {
			die('Error with <b>PHP Data Objects</b> : ' . $error->getMessage());
		}

		$SQLRequest = file_get_contents($siteDir . 'NessCMS.sql');

		$request = $db->prepare($SQLRequest);
		$request->execute([$_POST['site_name'], trim(stripslashes(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME)), '/')]);

		unlink(__FILE__);
		unlink($siteDir . 'NessCMS.sql');

		header('Location: ./');
	}
	else {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico">
		<title>NessCMS - Install</title>
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<style>
			footer {
				border-top: 1px solid #eee;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<header class="page-header">
						<h1>The installation process of NessCMS</h1>
					</header>

					<section>
						<div class="row">
							<form class="form-horizontal col-lg-offset-1 col-lg-10" method="post" action="">
								<div class="row">
									<div class="form-group">
										<label class="col-lg-3" for="site_name">Site name</label>
										<div class="col-lg-9">
											<input id="site_name" name="site_name" type="text" placeholder="E.g.: My extra website" class="form-control" required>
										</div>
									</div>
								</div>

<?php
								if (!file_exists($configFile)) {
?>
									<fieldset class="col-lg-12">
										<legend>Database</legend>

										<div class="row">
											<div class="form-group">
												<label class="col-lg-offset-1 col-lg-2" for="host">Host</label>
												<div class="col-lg-8">
													<input id="host" name="host" type="text" placeholder="Mostly “localhost”" class="form-control" value="localhost" required>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group">
												<label class="col-lg-offset-1 col-lg-2" for="name">Name</label>
												<div class="col-lg-8">
													<input id="name" name="name" type="text" placeholder="Your database name (MUST BE CREATED!)" class="form-control" required>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group">
												<label class="col-lg-offset-1 col-lg-2" for="user">User</label>
												<div class="col-lg-8">
													<input id="user" name="user" type="text" placeholder="Your database account's name" class="form-control" required>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group">
												<label class="col-lg-offset-1 col-lg-2" for="pass">Password</label>
												<div class="col-lg-8">
													<input id="pass" name="pass" type="password" placeholder="Your database account's password" class="form-control" required>
												</div>
											</div>
										</div>
									</fieldset>
<?php
								}
?>

								<div class="pull-right form-group">
									<button class="btn btn-primary">Create my website!</button>
								</div>
							</form>
						</div>
					</section>

					<footer class="text-center">
						2013 - 2014 <strong>Tao Schreiner</strong> - Licensed <a href="./LICENSE">GPL v3</a>.
					</footer>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	}