<!DOCTYPE html>
<html>
	<head>
		<?php \Basics\Templates::basicHeaders(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $subDir; ?>images/favicon.ico">
		<title><?php echo $siteName; ?> | <?php echo $pageTitle . ' - ' . $clauses->get('admin'); ?></title>
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="<?php echo $subDir . $theme['dir']; ?>css/styles.css">
		<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
		<script src="<?php echo $subDir; ?>js/typeahead.min.js"></script>
		<script>
			tinymce.init({
				selector: 'textarea.tinymce', theme: 'modern',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks visualchars code fullscreen',
					'insertdatetime media nonbreaking save table contextmenu directionality',
					'emoticons template paste textcolor colorpicker textpattern imagetools'
				],
				toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
				toolbar2: 'link image | print preview media | forecolor backcolor emoticons', image_advtab: true,
				language_url : '<?php echo $subDir; ?>js/tinymce.<?php echo $language; ?>.js'
			});

			function tagAddFailed() {
				alert('<?php echo $clauses->get('tag_already_chosen'); ?>');
			}
		</script>
		<script src="<?php echo $subDir . $theme['dir']; ?>js/scripts.js"></script>
	</head>
	<body>
		<header class="container">
			<nav role="banner" class="navbar navbar-default">
				<div class="navbar-header pull-left">
					<a href="<?php echo $linksDir; ?>index" class="navbar-brand"><?php echo $siteName; ?></a>
				</div>

				<ul class="nav navbar-nav lang-selector pull-left">
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
<?php
				if ($rights['admin_access']) {
?>
					<div class="row">
						<div class="col-lg-12">
							<ul class="nav nav-pills nav-justified">
<?php
								foreach ($navigation as $item) {
									echo '<li';
										if ('admin/' . $params[1] === str_replace('/index', null, $item['link']) OR ($item['link'] === 'admin/index' AND $params[1] === 'index'))
											echo ' class="active"';
									echo '><a href="' . $linksDir . $item['link'] . '">' . $item['caption'] . '</a></li>' . PHP_EOL;
								}
?>
							</ul>
						</div>
					</div>

					<hr>
<?php
				}
?>

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