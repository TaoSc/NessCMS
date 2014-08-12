<!DOCTYPE html>
<html>
	<head>
		<?php \Basics\Templates::basicHeaders(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $subDir; ?>images/favicon.ico">
		<title><?php echo $siteName; ?> | <?php echo $pageTitle . ' - ' . $clauses->get('admin'); ?></title>
		<!--[if lt IE 9]>
			<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style>
			body {
				padding-top: 10px;
			}
		</style>
	</head>
	<body>
		<header class="container">
			<nav role="banner" class="navbar navbar-default">
				<div class="navbar-header pull-left">
					<a href="<?php echo $linksDir; ?>index" class="navbar-brand"><?php echo $siteName; ?></a>
				</div>
			</nav>
		</header>

		<div class="container side-collapse-container">
			<section>
				<div class="row">
					<div class="col-lg-12">
						<ul class="nav nav-pills nav-justified">
<?php
							foreach ($navigation as $item) {
								echo '<li';
									if ($item['link'] === $location)
										echo ' class="active"';
								echo '><a href="' . $linksDir . $item['link'] . '">' . $item['caption'] . '</a></li>' . PHP_EOL;
							}
?>
						</ul>
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col-lg-12">
						<?php include $siteDir . 'themes/admin/views/' . $viewPath . '.php'; ?>
					</div>
				</div>
			</section>
		</div>

		<footer class="container">
			<div class="col-lg-12 text-center">
				<a href="//github.com/TaoSc/NessCMS">Source code</a> - Licensed <a href="<?php echo $linksDir; ?>LICENSE">GPL v3</a>.
			</div>
		</footer>
	</body>
</html>