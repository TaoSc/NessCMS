<!DOCTYPE html>
<html>
	<head>
		<?php \Basics\Templates::basicHeaders(); ?>
		<script src="//tinymce.cachefly.net/4/tinymce.min.js"></script>
		<script>tinymce.init({selector: 'textarea.tinymce', language_url : '<?php echo $subDir; ?>js/tinymce.<?php echo $language; ?>.js'});</script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $subDir; ?>images/favicon.ico">
		<title><?php echo $siteName; ?> | <?php echo $pageTitle . ' - ' . $clauses->get('admin'); ?></title>
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style>
			body {
				padding-top: 10px;
			}

			.btn-outline {
				background-color: transparent;
				color: inherit;
				transition: all .5s;
			}
			.btn-primary.btn-outline {
				color: #428bca;
			}
			.btn-success.btn-outline {
				color: #5cb85c;
			}
			.btn-info.btn-outline {
				color: #5bc0de;
			}
			.btn-warning.btn-outline {
				color: #f0ad4e;
			}
			.btn-danger.btn-outline {
				color: #d9534f;
			}
			.btn-primary.btn-outline:hover,
			.btn-success.btn-outline:hover,
			.btn-info.btn-outline:hover,
			.btn-warning.btn-outline:hover,
			.btn-danger.btn-outline:hover {
				color: #fff;
			}
		</style>
	</head>
	<body>
		<header class="container">
			<nav role="banner" class="navbar navbar-default">
				<div class="navbar-header">
					<a href="<?php echo $linksDir; ?>index" class="navbar-brand"><?php echo $siteName; ?></a>
				</div>

				<ul class="nav navbar-nav lang-selector">
					<li class="dropdown">
						<a data-toggle="dropdown" href="#null"><?php echo $actualLang['country_name']; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
<?php
							foreach ($languagesList as $languageLoop)
								echo '<li>
									<a href="' . $linksDir . 'lang/' . $languageLoop['code'] . '/' . str_replace('%', '=', urlencode($location)) . '">
										<span class="sprites ' . $languageLoop['code'] . ' flag"></span>' . $languageLoop['name'] . '
									</a>
								</li>';
?>
						</ul>
					</li>
				</ul>
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
<?php
						if (isset($btnsGroupMenu) AND !empty($btnsGroupMenu)) {
?>
							<div class="btn-group btn-group-justified btn-group-sm">
<?php
								foreach ($btnsGroupMenu as $btnsGroupElem) {
									echo '<a href="' . $btnsGroupElem['link'] . '" type="button" class="btn btn-';
									if (isset($btnsGroupElem['type']))
										echo $btnsGroupElem['type'];
									else
										echo 'primary btn-outline';
									echo '">' . $btnsGroupElem['name'] . '</a>';
								}
?>
							</div>
							<hr>
<?php
						}

						include $viewPath;
?>
					</div>
				</div>
			</section>
		</div>

		<hr>

		<footer class="container">
			<div class="col-lg-12 text-center">
				<a href="//github.com/TaoSc/NessCMS"><?php echo $clauses->get('source_code'); ?></a> - Licensed <a href="<?php echo $subDir; ?>LICENSE">GPL v3</a>.
			</div>
		</footer>
	</body>
</html>