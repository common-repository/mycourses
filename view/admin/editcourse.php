<div class="wrap">
	<h2><?php _e('Courses', 'courses') ?></h2>

	<fieldset class="edit-course">
		<legend>Edit Course</legend>
		<form method="post" action="<?php echo $this->url($_SERVER['REQUEST_URI']) ?>">

		<?php $this->render_admin ('course', array('url' => $url, 'course' => $course)) ?>

		<p class="submit">
			<input type="submit" name="updatecourse" value="<?php _e ('Update Course', 'courses') ?>"/>
		</p>

		</form>
	</fieldset>
</div>