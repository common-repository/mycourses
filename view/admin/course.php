<table class="optiontable">
	<tr valign="top">
		<th scope="row">Course Name:</th>
		<td colspan="3"><input name="name" type="text" id="name" value="<?php echo htmlentities($course->name) ?>" size="40" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Location:</th>
		<td colspan="3"><input name="location" type="text" id="location" value="<?php echo htmlentities($course->location) ?>" size="40" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Address:</th>
		<td colspan="3"><input name="address" type="text" id="address" value="<?php echo htmlentities($course->address) ?>" size="40" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Start Date:</th>
		<td colspan="3"><input name="start_date" type="text" id="start_date" value="<?php echo htmlentities($course->start_date) ?>" size="14" class="date-pick" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">End Date:</th>
		<td colspan="3"><input name="end_date" type="text" id="end_date" value="<?php echo htmlentities($course->end_date) ?>" size="14" class="date-pick" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td colspan="3">&nbsp;</td>
	</tr>
	<?php
	$course_times = $course->course_times;
	if (count($course_times) > 0) {
		$course_time = $course_times[0];
		$day = $course_time->day;
		$start_time= $course_time->start_time;
		$end_time = $course_time->end_time;
	}
	else {
		$day = '';
		$start_time = '';
		$end_time = '';
	}
	?>
	<tr valign="top">
		<th scope="row">Day/Start Time/End Time:</th>
		<td><input name="day[]" type="text" id="day" value="<?php echo htmlentities($day) ?>" size="20" /><br />e.g. Thursday</td>
		<td><input name="start_time[]" type="text" id="start_time" value="<?php echo htmlentities($start_time) ?>" size="10" /><br />14:45</td>
		<td><input name="end_time[]" type="text" id="end_time" value="<?php echo htmlentities($end_time) ?>" size="10" /><br />16:30</td>
	</tr>
	<?php
	if (isset($course)) {
		$url .= '&amp;id=' . $course->id;
	}
	
	$times = 1; // Default
	if (isset($_GET['times'])) {
		$times = $_GET['times'];
	}
	elseif (count($course_times) > 1) {
		$times = count($course_times) - 1;
	}
	
	for ($i = 1; $i <= $times; $i++):
		if (isset($course_times[$i])) {
			$course_time = $course_times[$i];
			$day = $course_time->day;
			$start_time= $course_time->start_time;
			$end_time = $course_time->end_time;
		}
		else {
			$day = '';
			$start_time = '';
			$end_time = '';
		}
		?>
		<tr valign="top" class="time">
			<th scope="row">&nbsp;</th>
			<td><input name="day[]" type="text" id="day" value="<?php echo htmlentities($day) ?>" size="20" /></td>
			<td><input name="start_time[]" type="text" id="start_time" value="<?php echo htmlentities($start_time) ?>" size="10" /></td>
			<td><input name="end_time[]" type="text" id="end_time" value="<?php echo htmlentities($end_time) ?>" size="10" /></td>
		</tr>
	<?php endfor; ?>
	<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td colspan="3"><a href="<?php echo $url ?>&amp;times=<?php echo $times + 1 ?>" class="add-day">Add another day &amp; time</a></td>
	</tr>
</table>
