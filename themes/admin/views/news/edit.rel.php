<form class="form-horizontal col-lg-12" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10">
		<legend><?php echo $clauses->get($params[2] === '0' ? 'create_news' : 'edit_news'); ?></legend>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="title"><?php echo $clauses->get('title'); ?></label>
			<div class="col-xs-4">
				<input name="title" id="title" type="text" class="form-control" value="<?php if (!$create) echo $news['title']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="sub_title"><?php echo $clauses->get('sub_title'); ?></label>
			<div class="col-xs-4">
				<input name="sub_title" id="sub_title" type="text" class="form-control" value="<?php if (!$create) echo $news['sub_title']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="content"><?php echo $clauses->get('content'); ?></label>
			<div class="col-xs-8">
				<textarea id="content" name="content" class="form-control" rows="15" required><?php if (!$create) echo $news['content']; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="category_id"><?php echo $clauses->get('category'); ?></label>
			<div class="col-xs-4">
				<select id="category_id" name="category_id" class="form-control">
<?php
					foreach ($categories as $categoryLoop) {
						echo '<option value="' .  $categoryLoop['id'] . '"';
						if (!$create AND $news['category']['id'] === $categoryLoop['id']) echo ' selected';
						echo '>' .  $categoryLoop['title'] . '</option>' . PHP_EOL;
					}
?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="img"><?php echo $clauses->get('image'); ?></label>
			<div class="col-xs-4">
				<input name="img" id="img" type="url" class="form-control" placeholder="<?php echo $clauses->get('img_placeholder'); ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="visible"><?php echo $clauses->get('make_visible'); ?></label>
			<div class="col-xs-4">
				<div class="checkbox">
					<label for="visible">
						<input type="checkbox" name="visible" id="visible" value="on"<?php if (!$create AND $news['visible']) echo 'checked'; ?>>
						<?php echo $clauses->get('enable'); ?>
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-xs-offset-4" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>