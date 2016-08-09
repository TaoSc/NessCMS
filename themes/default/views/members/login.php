<div class="row">
	<form class="form-horizontal col-lg-12" method="post" action="">
		<fieldset class="col-lg-12">
			<legend><?php echo $clauses->get('log_in'); ?></legend>

			<div class="col-lg-offset-1 col-lg-10">
				<div class="row">
					<div class="form-group">
						<label class="col-lg-3" for="name"><?php echo $clauses->get('name_login'); ?></label>
						<div class="col-lg-9">
							<input id="name" name="name" type="text" class="form-control" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label class="col-lg-3" for="pwd"><?php echo $clauses->get('password'); ?></label>
						<div class="col-lg-9">
							<input id="pwd" name="pwd" type="password" class="form-control" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label class="col-lg-3" for="cookies"><?php echo $clauses->get('cookies_login'); ?></label>
						<div class="col-lg-9">
							<div class="checkbox">
								<label for="cookies">
									<input type="checkbox" name="cookies" id="cookies" value="on" checked>
									<span class="hel-neue-light">* <?php echo $clauses->get('cookies_login_info'); ?></span>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="pull-right form-group">
					<a href="<?php echo $linksDir; ?>members/registration" class="btn btn-link"><?php echo $clauses->get('register'); ?></a>
					<button class="btn btn-primary"><?php echo $clauses->get('log_in'); ?></button>
				</div>
			</div>
	</form>
</div>
