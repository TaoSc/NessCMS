<?php
	if (PHP_VERSION_ID < 50600)
		die('Your version of PHP is too old. Please use PHP 5.6 at least.');
	$language = 'en-us';

	if (pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME) === 'install.php')
		header('Location: ./');
	elseif (!file_exists($configFile) AND isset($_POST['host']) AND isset($_POST['name']) AND isset($_POST['user']) AND isset($_POST['pass'])) {
		file_put_contents($configFile, '<?php $dbHost = \'' . $_POST['host'] . '\';$dbName = \'' . $_POST['name'] . '\';$dbUser = \'' . $_POST['user'] . '\';$dbPass = \'' . $_POST['pass'] . '\';');
		header('Refresh: 0');
	}
	elseif (isset($_POST['nickname']) AND isset($_POST['email']) AND isset($_POST['pwd']) AND isset($_POST['pwd2']) AND isset($_POST['site_name'])) {
		$request = $db->prepare(file_get_contents($siteDir . 'NessCMS.sql'));
		$request->execute([$_POST['site_name'], trim(stripslashes(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME)), '/')]);

		$request = $db->query('UPDATE members SET id = 0 WHERE id = 1');

		$clauses = new Basics\Languages($language);

		if (!Members\Handling::registration($_POST['nickname'], $_POST['email'], $_POST['pwd'], $_POST['pwd2'], false, true))
			die('We were unable to create your account. <b>Please retry.</b>');

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
							<form class="form-horizontal col-lg-12" method="post" action="">
<?php
								if (file_exists($configFile)) {
?>
								<div class="row">
									<div class="form-group">
										<label class="col-xs-4 control-label" for="site_name">Site name</label>
										<div class="col-xs-4">
											<input id="site_name" name="site_name" type="text" placeholder="E.g.: “My extra website”" class="form-control" required>
										</div>
									</div>
								</div>

								<fieldset class="col-lg-offset-1 col-lg-10">
									<legend>Your account</legend>

									<div class="form-group">
										<label class="col-xs-4 control-label" for="nickname">Nickname</label>
										<div class="col-xs-4">
											<input id="nickname" name="nickname" type="text" class="form-control" required>
										</div>
									</div>

									<div class="form-group">
										<label class="col-xs-4 control-label" for="email">E-mail</label>
										<div class="col-xs-4">
											<input id="email" name="email" type="email" class="form-control" required>
										</div>
									</div>

									<div class="form-group">
										<label class="col-xs-4 control-label" for="pwd">Password</label>
										<div class="col-xs-4">
											<input id="pwd" name="pwd" type="password" class="form-control" required>
										</div>
									</div>

									<div class="form-group">
										<label class="col-xs-4 control-label" for="pwd2">Password (checking)</label>
										<div class="col-xs-4">
											<input id="pwd2" name="pwd2" type="password" class="form-control" required>
										</div>
									</div>
								</fieldset>

<?php
								}
								else {
?>
									<fieldset class="col-lg-offset-1 col-lg-10">
										<legend>Database</legend>

										<div class="form-group">
											<label class="col-xs-4 control-label" for="host">Host</label>
											<div class="col-xs-4">
												<input id="host" name="host" type="text" placeholder="Mostly “localhost”" class="form-control" value="localhost" required>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-4 control-label" for="name">Name</label>
											<div class="col-xs-4">
												<input id="name" name="name" type="text" placeholder="Your database name (muste be created)" class="form-control" required>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-4 control-label" for="user">User</label>
											<div class="col-xs-4">
												<input id="user" name="user" type="text" placeholder="Your database account's name" class="form-control" required>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-4 control-label" for="pass">Password</label>
											<div class="col-xs-4">
												<input id="pass" name="pass" type="password" placeholder="Your database account's password" class="form-control" required>
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