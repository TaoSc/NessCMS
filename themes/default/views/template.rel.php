<!DOCTYPE html>
<html>
	<head>
		<?php \Basics\Templates::basicHeaders(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $subDir; ?>images/favicon.ico">
		<title><?php echo $siteName; ?> | <?php echo $pageTitle; ?></title>
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="<?php echo $subDir . $theme['dir']; ?>css/styles.css">
		<script src="<?php echo $subDir . $theme['dir']; ?>js/jquery.color.min.js"></script>
		<script src="<?php echo $subDir . $theme['dir']; ?>js/jquery.lazyload.min.js"></script>
		<script src="<?php echo $subDir . $theme['dir']; ?>js/scripts.js"></script>
	</head>
	<body>
		<header role="banner" class="navbar navbar-fixed-top navbar-inverse">
			<div class="container">
				<div class="navbar-header pull-left">
					<a href="<?php echo $linksDir; ?>index" class="navbar-brand"><?php echo $siteName; ?></a>

					<button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container" type="button" class="navbar-toggle pull-left">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<nav role="navigation" class="navbar-collapse pull-right">
					<ul class="pull-left nav navbar-nav lang-selector">
						<li class="dropdown">
							<a data-toggle="dropdown" href="#null">
								<span class="sprites <?php echo $actualLang['code']; ?> flag"></span>
								<?php echo $actualLang['country_name']; ?>
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
<?php
								foreach ($languagesList as $languageLoop)
									echo '<li>
										<a href="' . $linksDir . 'lang/' . $languageLoop['code'] . '/' . str_replace('%', '=', urlencode($location)) . '">
											<span class="sprites ' . $languageLoop['code'] . ' flag"></span>' . $languageLoop['name'] . '
										</a>
									</li>';
								if ($languagesList)
									echo '<li class="divider"></li>';
?>
								<li><a href="#null"><span class="sprites unknown flag"></span><?php echo $clauses->get('lang_add'); ?></a></li>
							</ul>
						</li>
					</ul>
<?php
					if ($currentMemberId) {
?>
						<div class="pull-right btn-group navbar-btn login-btn btn-group-sm">
							<a href="<?php echo $linksDir . 'members/' . $currentMember['slug']; ?>/" class="btn btn-default">
								<img src="<?php echo Basics\Templates::getImg('avatars/' . $currentMember['avatar_slug'], $currentMember['avatar'], 100, 100); ?>" alt="<?php echo $clauses->get('avatar'); ?>" class="img img-circle">
								<?php echo $currentMember['nickname']; ?>
							</a>
							<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>

							<ul class="dropdown-menu pull-right">
								<li><a href="<?php echo $linksDir . 'admin/members/' . $currentMember['id']; ?>"><span class="glyphicon glyphicon-user"></span> <?php echo $clauses->get('modify_profile'); ?></a></li>
<?php
								if ($rights['admin_access'])
									echo '<li><a href="' . $linksDir . 'admin/' . '"><span class="glyphicon glyphicon-wrench"></span> ' . $clauses->get('admin') . '</a></li>';
?>
								<li><a href="//github.com/TaoSc/NessCMS/issues"><span class="glyphicon glyphicon-warning-sign"></span> <?php echo $clauses->get('report_bug'); ?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo $linksDir . 'members/logout/' . str_replace('%', '=', urlencode($location)); ?>"><span class="glyphicon glyphicon-log-out"></span> <?php echo $clauses->get('log_out'); ?></a></li>
							</ul>
						</div>
<?php
					}
					else {
?>
						<button data-toggle="modal" data-target="#login" href="<?php echo $linksDir . 'members/login/ajax/' . str_replace('%', '=', urlencode($location)); ?>" class="pull-right btn btn-primary navbar-btn login-btn" data-loading-text="<?php echo $clauses->get('loading'); ?>">
							<span class="glyphicon glyphicon-user"></span>
							<?php echo $clauses->get('login'); ?>
						</button>
<?php
					}
?>
				</nav>

				<div class="navbar-inverse side-collapse in">
					<nav role="navigation" class="navbar-collapse">
						<ul class="nav navbar-nav">
<?php
							foreach ($navigation as $item) {
								echo '<li';
									if ($item['link'] === $location)
										echo ' class="active"';
									if ($item['link'] === 'forum/index')
										echo ' class="disabled"';
								echo '><a href="' . $linksDir . $item['link'] . '">' . $item['caption'] . '</a></li>' . PHP_EOL;
							}
?>
						</ul>

						<div class="col-sm-3">
							<form class="navbar-form" method="get" action="<?php echo $linksDir; ?>search">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="<?php echo $clauses->get('search_placeholder'); ?>" name="query">
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
									</div>
								</div>
							</form>
						</div>
					</nav>
				</div>
			</div>
		</header>

		<div class="container side-collapse-container">
			<section>
<?php
				if (isset($breadcrumb)) {
?>
					<div class="row">
						<div class="col-lg-12">
							<ul class="breadcrumb">
								<li class="disabled"><?php echo $siteName; ?></li>
<?php
								foreach ($breadcrumb as $helperElem) {
									if ($breadcrumb[count($breadcrumb) - 1]['name'] === $helperElem['name'])
										echo '<li class="active">' . $helperElem['name'];
									elseif (isset($helperElem['link']))
										echo '<li><a href="' . $linksDir . $helperElem['link'] . '">' . $helperElem['name'] . '</a>';
									else
										echo '<li>' . $helperElem['name'];
									echo '</li>' . PHP_EOL;
								}
?>
							</ul>
						</div>
					</div>
<?php
				}

				include $viewPath;
?>
			</section>
		</div>

		<footer class="container">
			<div class="col-lg-12 text-center">
				2013 - <?php echo date('Y'); ?> <strong>Tao Schreiner</strong> - Licensed <a href="<?php echo $subDir . 'LICENSE'; ?>">GPL v3</a>.
			</div>
		</footer>
<?php
		if (!$currentMemberId) {
?>
			<div class="modal fade" id="login">
				<div class="modal-dialog">
					<div class="modal-content"></div>
				</div>
			</div>
<?php
		}
?>
	</body>
</html>