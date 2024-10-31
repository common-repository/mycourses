<div class="wrap">
	<h2><?php _e('Courses', 'courses') ?></h2>
	
	<?php if (count($courses) > 0): ?>
	<fieldset class="course-list">
		<form method="post" action="<?php echo $this->url($_SERVER['REQUEST_URI']) ?>">
		<table>
			<tr>
				<th>&nbsp;</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Course Name</th>
				<th>Location</th>
				<th>&nbsp;</th>
			</tr>
			<?php foreach ($courses as $course): ?>
			<tr class="course">
				<td class="checkbox"><input type="checkbox" name="deleteids[]" value="<?php echo $course->id ?>" id="chk-course-<?php echo $course->id ?>" /></td>
				<td class="start-date"><label for="chk-course-<?php echo $course->id ?>"><?php echo $course->start_date ?></label></td>
				<td class="end-date"><label for="chk-course-<?php echo $course->id ?>"><?php echo $course->end_date ?></label></td>
				<td class="name"><label for="chk-course-<?php echo $course->id ?>"><?php echo $course->name ?></label></td>
				<td class="location"><label for="chk-course-<?php echo $course->id ?>"><?php echo $course->location ?></label></td>
				<td class="action"><a href="<?php echo $url ?>&amp;sub=editcourse&amp;id=<?php echo $course->id ?>">Edit</a></td>
			</tr>
			<?php endforeach; ?>
		</table>

		<p class="submit">
			<input type="submit" name="deletecourses" value="<?php _e ('Delete Selected Courses', 'courses') ?>"/>
		</p>

		</form>
	</fieldset>
	<?php endif; ?>

	<fieldset class="add-course">
		<legend>Add New Course</legend>
		<form method="post" action="<?php echo $this->url($_SERVER['REQUEST_URI']) ?>">
		<?php $this->render_admin ('course', array('url' => $url)) ?>

		<p class="submit">
			<input type="submit" name="addcourse" value="<?php _e ('Add Course', 'courses') ?>"/>
		</p>

		</form>
	</fieldset>
</div>
