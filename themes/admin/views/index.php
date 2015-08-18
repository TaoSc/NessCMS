<p><?php echo $clauses->get('admin_index'); ?></p>

<form class="form-horizontal col-lg-12" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10">
		<legend><?php echo $clauses->get('personalize'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="index_text"><?php echo $clauses->get('index_text_label'); ?></label>
			<div class="col-md-8 col-xs-9">
				<textarea id="index_text" name="index_text" class="form-control tinymce" rows="15"><?php echo $indexText; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<div class="col-xs-offset-4" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>