<form class="form-horizontal col-lg-12" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10">
		<legend><?php echo $clauses->get($create ? 'create_news' : 'edit_news'); ?></legend>

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
				<textarea id="content" name="content" class="form-control tinymce" rows="15"><?php if (!$create) echo $news['content']; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="category_id"><?php echo $clauses->get('category'); ?></label>
			<div class="col-xs-4">
				<select id="category_id" name="category_id" class="form-control">
<?php
					foreach ($categories as $categoryLoop) {
						echo '<option value="' .  $categoryLoop['id'] . '"';
						if ((!$create AND $news['category']['id'] === $categoryLoop['id']) OR ($create AND $categoryLoop['id'] == 1)) echo ' selected';
						echo '>' .  $categoryLoop['name'] . '</option>' . PHP_EOL;
					}
?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="tags"><?php echo $clauses->get('tags'); ?></label>
			<div class="col-xs-4">
				<div class="input-group">
					<div class="input-group-btn input-group-select">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="concept"><?php echo $clauses->get($firstTagsType); ?></span> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu tags-types" role="menu">
<?php
							foreach ($tagsTypes as $tagType)
								echo '<li><a href="#' .  $tagType . '">' .  $clauses->get($tagType) . '</a></li>' . PHP_EOL;
?>
						</ul>
						<input type="hidden" class="input-group-select-val" name="tag-type-temp" value="<?php echo $firstTagsType; ?>" disabled>
						<input type="hidden" class="input-group-select-val" name="tag-id-temp" value="" disabled>
						<input type="hidden" class="input-group-select-val" name="tags" value="<?php if (!$create) echo json_encode($tagsIds); else echo '[]'; ?>">
					</div>
					<input type="text" name="tag-temp" class="form-control">
					<span class="input-group-btn">
						<button type="button" class="btn btn-success btn-add" disabled>+</button>
					</span>
				</div>
			</div>
			<div class="col-xs-offset-4 col-xs-8 tags-chosen">
<?php
				if (!$create) {
					foreach ($news['tags'] as $tagLoop) {
?>
						<span class="tag-label" data-id="<?php echo $tagLoop['id']; ?>"><span class="label label-primary"><span class="glyphicon glyphicon-tag small"></span> <?php echo $tagLoop['name']; ?></span><a class="btn btn-xs icon-btn btn-muted btn-remove" href="#remove"><span class="glyphicon glyphicon-remove text-danger small"></span></a></span>
<?php
					}
				}
?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="img"><?php echo $clauses->get('image'); ?></label>
			<div class="col-xs-4">
				<input name="img" id="img" type="url" class="form-control" placeholder="<?php echo $clauses->get('img_placeholder') . '"'; if ($create) echo ' required'; ?>>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="visible"><?php echo $clauses->get('visibility'); ?></label>
			<div class="col-xs-4">
<?php
				if ($rights['news_publish']) {
?>
					<div class="checkbox">
						<label for="visible">
							<input type="checkbox" name="visible" id="visible" value="on"<?php if (!$create AND $news['visible']) echo ' checked'; ?>>
							<?php echo $clauses->get('enable'); ?>
						</label>
					</div>
<?php
				}
				if (!$create) {
?>
					<div class="checkbox">
						<label for="availability">
							<input type="checkbox" name="availability" id="availability" value="<?php if ($news['default_language'] === $language) echo 'default" disabled'; else echo 'on"'; if (!$create AND $news['availability']) echo ' checked'; ?>>
							<?php echo $clauses->get('enable_for_language'); ?>
						</label>
					</div>
<?php
				}
?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="priority"><?php echo $clauses->get('priority'); ?></label>
			<div class="col-xs-4">
				<select id="priority" name="priority" class="form-control">
<?php
					foreach ($postsPriorities as $priorityLoop) {
						echo '<option value="' .  $priorityLoop . '"';
						if (!$create AND $news['priority'] === $priorityLoop) echo ' selected';
						echo '>' .  $clauses->get($priorityLoop) . '</option>' . PHP_EOL;
					}
?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="comments"><?php echo $clauses->get('enable_comments'); ?></label>
			<div class="col-xs-4">
				<div class="checkbox">
					<label for="comments">
						<input type="checkbox" name="comments" id="comments" value="on"<?php if (!$create AND $news['comments']) echo ' checked'; ?>>
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